Kindlasti! Siin on veelgi **sisukam** ja **praktilisem** õppematerjal turvapoliitika ja protseduuride teemal, tuues välja iga osa juurde kaks selget näidet reaalsest rakendamisest.

---

## Turvapoliitika ja Protseduurid: Sisukas Praktiline Õppematerjal 🛡️

### 1. Turvapoliitika: Organisatsiooni Pühendumus ja Juhtimise Raamistik

**Turvapoliitika** on kõrgeimal tasemel ametlik dokument, mis väljendab juhtkonna pühendumust küberturvalisusele. See on **miks** ja **mida** peame kaitsma ning loob õigusliku ja haldusliku aluse kogu turvaprogrammile.

#### 1.1. Konfidentsiaalsuse, Tervikluse ja Kättesaadavuse (KTK/CIA Triad) Strateegiad

Poliitika peab detailselt määratlema, kuidas kaitstakse andmete kolme põhiatribuuti.

| Atribuut | Strateegia | Praktiline Rakendus 1 (Tehniline Jõustamine) | Praktiline Rakendus 2 (Halduslik Jõustamine) |
| :--- | :--- | :--- | :--- |
| **Konfidentsiaalsus** | **Eesmärk:** Tagada juurdepääs ainult volitatud isikutele. | **Juurdepääsukontroll (RBAC - inglise keeles Role-Based Access Control):** Poliitika nõuab, et kõigile kriitilistele süsteemidele rakendatakse **Rollipõhist Juurdepääsukontrolli (RBAC)**, kus juurdepääs andmetele antakse *ainult ametikoha ja vajaduse* alusel (Need-to-Know). | **Mittetoimiva Töötaja Konto Külmutamine:** Poliitika nõuab IT-lt *automaatset 1-tunnilist viivitust* konto deaktiveerimisel peale töölepingu lõppemist, et vältida volitamata juurdepääsu endisele töötajale viimaste tundide jooksul. |
| **Terviklus** | **Eesmärk:** Tagada andmete täpsus ja muutumatus. | **Muutuste Juhtimise Nõue (Change Control):** Poliitika nõuab, et enne **kõikide toodangukeskkonna muudatuste** (sh uue koodi juurutamine, serverite konfigureerimine) rakendamist peab *sõltumatu teine isik* (nt vanemadministraator või QA) muudatuse **üle vaatama ja kinnitama**. | **Auditilogi Kontroll:** Poliitika määrab, et **andmebaasi auditilogisid** kontrollitakse automaatselt *vähemalt kord nädalas* ebanormaalsete tegevuste (nt. suur hulk ühekordseid kustutamisi) tuvastamiseks. |
| **Kättesaadavus** | **Eesmärk:** Tagada süsteemide ja andmete ligipääs, kui neid vajatakse. | **Pidev Töökindlus (Clustering):** Poliitika nõuab, et kõik kasutajaid teenindavad kriitilised süsteemid (nt. e-posti server, CRM) peavad olema paigaldatud **klasterdatult** (mitu serverit töötavad koos), et *ühe komponendi rike ei tooks kaasa katkestust*. | **Taasteaja Eesmärgid (RTO/RPO):** Poliitika määratleb *iga süsteemi* jaoks ametlikult maksimaalse lubatud taasteaja (**RTO**) 4 tunniks ja maksimaalse lubatud andmekao (**RPO**) 1 tunniks. |

---

### 2. Teemaspetsiifilised Poliitikad: Keskendumine Konkreetsetele Riskidele

Need poliitikad on detailsemad ja keskenduvad spetsiifilistele ohuallikatele või käitumisele.

#### 2.1. Puhast Laua/Ekraani Poliitika (Clean Desk/Screen Policy) 📄

**Eesmärk:** Vähendada tundliku info füüsilist varguse või volitamata nägemise riski.

| Poliitika Nõue | Praktiline Rakendus 1 (Füüsiline turve) | Praktiline Rakendus 2 (Infoturbe kultuur) |
| :--- | :--- | :--- |
| **Töölaualt Lahkumise Reeglid** | **Automaatne Lukustamine:** Süsteemi haldus (GPO) seadistab kõigi tööarvutite ekraanid automaatselt lukustuma **5 minuti** passiivsuse järel, et vältida andmetele juhuslikku juurdepääsu. | **Väljaspool Tööaega:** Poliitika nõuab, et *kõik* paberdokumendid, märkmed ja mälupulgad peavad olema tööpäeva lõpus **lukustatud sahtlites või kappides**. |

#### 2.2. E-posti ja Interneti Kasutamise Poliitika 📧

**Eesmärk:** Kaitsta organisatsiooni pahavara, andmelekke ja juriidiliste riskide eest.

| Poliitika Nõue | Praktiline Rakendus 1 (E-posti turvalisus) | Praktiline Rakendus 2 (Interneti turvalisus) |
| :--- | :--- | :--- |
| **Meilimanuste Käitlemine** | **Sandboxing:** Poliitika nõuab, et kõik tundmatutest allikatest tulevad e-posti **manused avatakse esmalt liivakastis (sandbox)**, mis isoleerib need organisatsiooni võrgustikust, enne kui kasutaja saab need alla laadida. | **Kategooriate Blokeerimine:** Poliitika keelab tööajal juurdepääsu **tööd mittepuudutavatele** veebikategooriatele (nt. hasartmängud, täiskasvanute sisu) **tulemüüri tasemel**, vähendades pahavara allalaadimise riski. |

---

### 3. Turvaprotseduurid: Samm-sammuline Juhis Poliitika Täitmiseks

**Turvaprotseduurid** on detailne **kuidas**-juhend, mis tagab poliitika järjepideva ja standardiseeritud täitmise. Need on rutiinsed operatiivjuhised.

#### 3.1. Andmeleke Intsidendi Lahendamise Protseduur 🚨

See protseduur (mis on tuletatud Intsidendihalduse Poliitikast) on kriitiline kiiruse ja järjekindluse tagamiseks.

| Protseduuri Samm | Praktiline Rakendus 1 (Esmane Reageerimine) | Praktiline Rakendus 2 (Tõendite Kogumine) |
| :--- | :--- | :--- |
| **Eraldamine ja Piiramine** | **Võrgu Kiirkorraldus:** Protseduur juhendab IT-meeskonda *koheselt eraldama* (võrgukaabel välja/port keelata) *kõik* seadmed, mis on seotud andmelekke allikaga, **dokumenteerides täpse aja** ja sündmuse logidesse. | **Forensiline Koopia:** Protseduur näeb ette, et enne kahtlustatava seadme väljalülitamist tuleb teha sellest **töömälu koopia (RAM dump)** ning seejärel luua **seadme kõvaketta täielik krüptitud koopia** forensilise analüüsi jaoks. |

#### 3.2. Töötaja Lahkumise Protseduur (Off-Boarding)

See protseduur tagab organisatsiooni andmete ja varade turvalise tagastamise ning juurdepääsude tühistamise.

| Protseduuri Samm | Praktiline Rakendus 1 (Tehniline Turve) | Praktiline Rakendus 2 (Varade Haldus) |
| :--- | :--- | :--- |
| **Juurdepääsu Tühistamine** | **Simultaanne Keelamine:** Protseduur nõuab, et IT-meeskond peab **täpsel lahkumise tunnil** *samaaegselt* keelama/kustutama töötaja konto **kõigis** süsteemides: Active Directory, pilveteenused (Google Workspace/MS 365), VPN ja füüsiline juurdepääs (läbipääsukaart). | **Üleandmise Kontroll-leht:** Protseduur nõuab, et lahkumise intervjuul täidetakse **ametlik kontroll-leht**, mis dokumenteerib *kõikide füüsiliste varade* (sülearvuti, telefon, ID-kaardilugeja) tagastamise ning *vastutuse* kriitiliste projektide eest. |

---

### 4. Poliitika Elutsükkel: Jõustamine, Audit ja Pidev Parendamine

Poliitika pole staatiline dokument. See peab olema **elav** ja pidevalt kontrollitav.

#### 4.1. Poliitika Jõustamine ja Distsiplinaarmeetmed

| Valdkond | Praktiline Rakendus 1 (Auhind/Karistus) | Praktiline Rakendus 2 (Tehniline Blokeerimine) |
| :--- | :--- | :--- |
| **Reeglite Järgimine** | **Positiivne ja Negatiivne Tagasiside:** Poliitika näeb ette, et *õngitsuskatsetest (phishing) edukalt teatanud* töötajaid premeeritakse (nt. lisapuhkepäevaga). Korduvalt õngitsuslõksu langenud töötajatele nõutakse **täiendavat, isikupärastatud turvakoolitust**. | **Tundliku Info Blokeerimine:** Andmelekke vältimise (DLP) süsteem on seadistatud **automaatselt blokeerima** kõik väljaminevad e-kirjad, mis sisaldavad suurt hulka tuvastatud isikukoodide, krediitkaardi numbrite või terviseandmetega faile. |

#### 4.2. Auditeerimine, Ülevaatus ja Ajakohastamine

| Valdkond | Praktiline Rakendus 1 (Vastavuse Testimine) | Praktiline Rakendus 2 (Poliitika Juhtimine) |
| :--- | :--- | :--- |
| **Pidev Kontroll** | **Kvartalipõhised Läbistustestid:** Poliitika nõuab **välist läbistustesti (penetration test)** *vähemalt kord kvartalis*, et tuvastada turvanõrkusi enne, kui pahatahtlikud ründajad seda teevad. | **Poliitika Versioonihaldus:** Kõik poliitika ja protseduuride dokumendid on paigutatud **ühisesse, versioonitud keskkonda (nt. SharePoint)** ja iga muudatus peab olema **nummerdatud (nt. v1.1, v1.2)** ning kinnitatud CISO poolt. |
