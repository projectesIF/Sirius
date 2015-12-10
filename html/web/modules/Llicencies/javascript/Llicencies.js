/**
 * @authors Josep Ferràndiz Farré <jferran6@xtec.cat>
 * @authors Joan Guillén Pelegay  <jguille2@xtec.cat>
 *
 * @par Llicència:
 * GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Llicencies
 * @version 1.0
 * @copyright Departament d'Ensenyament. Generalitat de Catalunya 2013-2014
 */

function getSelectedText(elementId) {
    var elt = document.getElementById(elementId);

    if (elt.selectedIndex == -1)
        return null;

    return elt.options[elt.selectedIndex].text;
}

function updateDisplay(show, hide1, hide2) {
    $(show).style.display = "block";
    $(hide1).style.display = "none";
    if (typeof (hide2) != "undefined" && hide2 !== null) {
        document.getElementById(hide2).style.display = "none";
    }

}

/*
 * Mostra o amaga l'enllaç al formulari de cerca i el propi formulari
 */
function updateSearchLink(show, hide, showSearchForm) {
    updateDisplay(show, hide);
    //$('detail').style.display="none";
    if (showSearchForm)
        document.getElementById('search').style.display = "block";
    else
        document.getElementById('search').style.display = "none";
}

/*
 * 
 */
function search() {
    if (document.getElementById('wait') != null)
        $('wait').update('<img src="images/ajax/indicator.white.gif" /> Cercant');
    if (document.getElementById('list') != null)
        $('list').style.display = "none";
    if (document.getElementById('estat') != null)
        var estat = document.getElementById('estat').value
    var p = {
        titol: document.getElementById('titol').value,
        autor: document.getElementById('autor').value,
        tema: document.getElementById('tema').value,
        tema_txt: getSelectedText('tema'),
        subtema: document.getElementById('subtema').value,
        subtema_txt: getSelectedText('subtema'),
        tipus: document.getElementById('tipus').value,
        tipus_txt: getSelectedText('tipus'),
        curs: document.getElementById('curs').value,
        curs_txt: getSelectedText('curs'),
        admin: document.getElementById('admin').value,
        estat: estat
    };

    var myAjax = new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=le&func=search", {
        parameters: p,
        onComplete: search_response,
        onFailure: search_failure
    });

    //updateDisplay('list', 'search');
    $('detail').style.display = "none";
}

function search_response(req) {
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    updateDisplay('list', 'search');
    if (document.getElementById('wait') != null)
        $('wait').update('');
    var b = req.getData();
    $('list').update(b.content);
}

function search_failure() {
}

function detail(code)
{
    var p = {codi_treball: code
    };

    $('wait').update('<img src="images/ajax/indicator.white.gif" /> Cercant');
    updateDisplay('showSearchLink', 'hideSearchLink');
    updateDisplay('detail', 'list', 'search');

    new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=le&func=detail", {
        parameters: p,
        onComplete: detail_response,
        onFailure: detail_failure
    });
}

function detail_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    $('wait').update('');
    var b = req.getData();
    $('detail').update(b.content);
}

function detail_failure()
{
}

function setDocRoot() {
    var p = {docRoot: document.getElementById('docRoot').value
    };
    //document.getElementById('btOk').style.display="none";

    new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=le&func=setDocRoot", {
        parameters: p,
        onComplete: setDocRoot_response,
        onFailure: setDocRoot_failure
    });

    $('content').update('<img src="images/ajax/indicator.white.gif" />');

}

function setDocRoot_response(req) {
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }

    var b = req.getData();
    $('content').update(b.content);
}

function setDocRoot_failure(req) {

}

function edit(code)
{
    var p = {codi_treball: code
    };

    //$('content').update('<img src="images/ajax/indicator.white.gif" /> Cercant');    

    updateDisplay('detail', 'list');
    new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=le&func=edit", {
        parameters: p,
        onComplete: edit_response,
        onFailure: edit_failure
    });
}

function edit_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var b = req.getData();
    $('detail').update(b.content);
}

function edit_failure()
{
}

function lupdate()
{
    var p = {
        codi_treball: document.getElementById('ecodi_treball').value,
        titol: document.getElementById('etitol').value,
        nom: document.getElementById('enom').value,
        cognoms: document.getElementById('ecognoms').value,
        correuel: document.getElementById('ecorreuel').value,
        modalitat: document.getElementById('emodalitat').value,
        nivell: document.getElementById('enivell').value,
        capsa: document.getElementById('ecapsa').value,
        tema: document.getElementById('etema').value,
        subtema: document.getElementById('esubtema').value,
        tipus: document.getElementById('etipus').value,
        curs: document.getElementById('ecurs').value,
        estat: document.getElementById('eestat').value,
        caracteristiques: document.getElementById('ecaracteristiques').value,
        orientacio: document.getElementById('eorientacio').value,
        resum: document.getElementById('eresum').value,
        url: document.getElementById('eurl').value,
        web: document.getElementById('eweb').value,
        annexos: document.getElementById('eannexos').value,
        altres: document.getElementById('ealtres').value
    };

    new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=le&func=update", {
        parameters: p,
        onComplete: lupdate_response,
        onFailure: lupdate_failure
    });
}

function lupdate_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    search();
    var b = req.getData();

    $('detail').update(b.content);
    $('detail').style.display = "block";
    $('list').style.display = "block";
}
function lupdate_failure(req)
{

}

function lremove(code)
{
    var c = confirm('Segur que vols eliminar el treball?');
    if (c == true) {
        var p = {codi_treball: code
        };

        //$('content').update('<img src="images/ajax/indicator.white.gif" /> Cercant');        
        //updateDisplay('detail', 'list');
        new Zikula.Ajax.Request(Zikula.Config.baseURL + "ajax.php?module=le&func=remove", {
            parameters: p,
            onComplete: remove_response,
            onFailure: remove_failure
        });
    }
}

function remove_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    //$('detail').style.display='block';
    var b = req.getData();
    search();
    //$(b.row).update(b.content);
    //$('detail').update(b.content);
}

function remove_failure()
{
}
