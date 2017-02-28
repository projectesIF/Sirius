Sirius 1.1.4
==============================

 - **Zikula 1.3.9** core (with catalan locale)

 - **Catàleg 1.1.3.1** module

 - **Llicencies 1.0.0**, **SiriusXtecAuth 1.0.1** and **SiriusXtecMailer 1.0.1** modules

 - IW modules (19) (default updated 2014/12/15): **IWagendas, IWbookings, IWbooks, IWdocmanager 3.0.1, IW forms 3.0.2, IWforums 3.1.0.1, IWgroups, IWjclic, IWmain 3.0.2.beta, IWmenu, IWmessages 3.0.1, IWmyrole, IWnoteboard, IWqv, IWstats, IWtimeframes, IWusers, IWvhmenu and IWwebbox**.

 - ZK modules (10): **Content 4.1.0, EZComments 3.0.1, Feeds 2.6.1, Files 1.0.3, Legal 2.0.1, News 3.0.1, Pages 2.5.1, Profile 1.6.1 and Scribite 5.0.0 (iw version)**

 - **IWcat2** 2.1 theme.

 - **Vendors**: *zikula1.4*, *bootstrap*, *datatables*, *csvImporter*, *jquery-ui*, *colorbox* (in javascript, style and images folder) i *mpdf* (dins mòdul Catàleg).
 

Changelog
=========

Master branch
-------------

Sirius 1.1.5 (2017/02/28)
-------------------------
  - Fix error in Cataleg_admin_addeditPrior.tpl (quotes forgotten that makes it unusable form) -> Cataleg 1.1.3.1
  - Update project docs and extra-html folders (only to document them)

Sirius 1.1.4 (2015/04/15)
-------------------------
  - Some fixes in Cataleg module (tag 1.1.3 from repo)
  - Fixing Files issue (upgrading to 1.0.3)
  - Upgrade IWcat2 theme to 2.1 - Adding css file include from Files folders
  - Deleting Downloads module. Now-> 32 modules (4 from Sirius and 28 form Intraweb Project)


Sirius 1.1.3 (2015/03/24)
------------------------
  - Upgrade Files module to 1.0.2 (copying files to root, Scribite and S5cribite)
  - Typo fixed in SiriusXtecAuth
  - Fixing bug in Llicencies (import function)
  - Cataleg 1.1.3 (1.1.2->adapted to Scribite v5,1.1.3->New features: editors manage 'unitats' and 'temàtiques')
  - Removing S5cribite and upgrading Scribite to 5.0.0 (from iw repo)
  - IWnoteboard 3.0.1
  - IWmessages 3.0.1
  - IWforms 3.0.2
  - IWforums 3.1.0 (3.0.1->New templates and 3.1.0->subscription system)
  - IWmain 3.0.2.beta (New cron system- User reports for IWforums and IWmessages)
  - Update catalan translation (issues fixed)

Sirius 1.1.2. (2014/12/17)
-------------------------
(2014/12/17 -> 1.1.2.2; 2014/12/15 -> 1.1.2) 
 - Working on IWforums 3.0.1 (not released yet, but functional like 3.0.0)
 - Update SiriusXtecAuth module to 1.0.1. It adds http basic authentification to apps.
 - Add SiriusXtecMailer module 1.0.1 (based on Agora XtecMailer).
 - Add S5cribite module (Scribite v5.0.0 clone to run v5 and v4 simultaneously)
 - Fixing IWmenu issue (changing z-index to show menu over admin panel)
 - Adding readme and licence files (sync with new github/intraweb-catalog projects) 

Sirius 1.1.1 (2014/11/13)
------------

 - Upgrade to zikula 1.3.9
 - Files 1.0.1 def (17/10/2014)
 - IWusers 3.1.0
   - Eines per esborrar usuaris
   - Importació/exportació d'usuaris
 - Afegint vendors: zikula1.4, bootstrap i datatables
 - Gestió de la separació dels cognoms al SiriusXtecAuth
 - Afegim posició de blocs al XTEC2 per treballar sense blocs laterals en alguna pantalla

 - Cataleg
   - Filtres a la gestió d'usuaris
   - Gestió dels nous grups d'usuaris
   - 1.1.1 Afegim variables de mòdul per a gestionar tots els grups d'usuaris
   - Gestió del 'code' gtaf des de la gestió d'usuaris
   - 1.1.0 Afegim taules per gestionar les entitats gtaf a Sirius 

- Resta de mòduls
   - Actualitzats tots a les darreres versions (zikula-modules, intraweb-modules13, craigh/Downloads)

Sirius 1.1.0 (2014/04/23)
------------

 - Actualització a Zikula 1.3.7
 - Actualització general de mòduls (treball específic amb el Files)
 - Nous mòduls SiriusXtecAuth i Llicencies
 - Càrrega dels mòduls IWstats, IWdocmanager, IWvhmenu, Feeds (plugin SimplePie)

Sirius 1.0.4
------------
Correcció de tres línies de codi pes esmenar un problema en la consulta que genera el pdf del catàleg

Sirius 1.0.3 (2013/06/20)
------------
 - Revisió i correcció de processos de cerca d'activitats i generació de pdf's en el detall d'activitats i orientacions de línies prioritàries.
 - Donar accés als administradors del catàleg (CatalegAdmin) a la generació del document en PDF de la totalitat d'un catàleg.
 - Revisió documentació i comentaris de funcions

Sirius 1.0.2 
------------
 - web/modules/Cataleg/lib/Cataleg/Controller/Admin.php - addeditUser()
 - web/modules/Cataleg/lib/Cataleg/Api/Admin.php - saveUser()
 - web/modules/Cataleg/templates/user/Cataleg_user_view.tpl -text quan en aplicar un filtre no hi ha contingut
 - web/modules/Cataleg/lib/...diversos...Procés de documentació de les funcions

Sirius 1.0.1 (2013/04/15)
------------
 - Primera versió estable de l'aplicatiu Sirius a l'entorn del Departament (1.0).
 - Permet ja la gestió i administració plena del Catàleg de Formació i el treball amb múltiples catàlegs.

Sirius 0.9 (2012/06/13)
----------
 - Versió inicial de l'aplicatiu Sirius a l'entorn del Departament.
 - Primera publicació del Catàleg Unificat de Formació.
 - No incorpora les eines d'administració del catàleg ni el treball en més d'un curs. 
 - Provinent de la versió 0.7 en l'entorn ENSE, on s'ha fet la primera construcció i edició del catàleg.

Sirius 0.7 (2012/06/06)
----------
 - Primera versió 'publicada' del catàleg
 - Segueix a l'entorn ENSE, on s'ha fet la primera construcció i edició del catàleg.

Sirius 0.5 (2012/05/18)
----------
 - Versió inicial de Sirius.
 - Configuracions i administració pujades manualment.
 - Els editors poden començar a introduir activitats i es pot realitzar el seguiment (validacions, esmenes...)
 - Es treballa en l'entorn ENSE


  - Notes i instruccions d'actualització a [gestform/docs/upgrade_1.3.x.md](gestform/docs/upgrade_1.3.x.md)

