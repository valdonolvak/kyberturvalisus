Siin on iseseisva töö ülesanded iga peatüki juurde, mis põhinevad tekstis toodud ründenäidetel ja põhimõtetel.

Lõpus on toodud **näidislahendus ülesandele nr 4 (Paroolide haldus)**.

---

### Iseseisva töö ülesanded

#### 1. Ülesanne: Tarkvara uuendamine ja WannaCry
**Situatsioon:** Tekst kirjeldab 2017. aasta WannaCry rünnakut, mis kasutas ära haavatavust, millele oli paik olemas juba 2 kuud varem.
**Ülesanne:** Koosta lühike ajajoon (timeline), mis kirjeldab Microsofti paiga (MS17-010) väljastamist ja rünnaku toimumist. Vasta küsimusele: Miks oli kahekuuline viivitus ettevõtete poolt kriitiline viga ja milline protseduur oleks selle ära hoidnud?

#### 2. Ülesanne: EDR ja käitumisanalüüs
**Situatsioon:** Finantsettevõtet rünnati pahavaraga, millel puudus viiruse allkiri, kuid EDR peatas selle käitumise põhjal.
**Ülesanne:** Kirjelda oma sõnadega, milline konkreetne tegevus (sündmus) reetis pahavara EDR-süsteemile? Miks vana kooli viirusetõrje (mis põhineb faili "sõrmejäljel" ehk *hashil*) oleks selle rünnaku läbi lasknud?

#### 3. Ülesanne: Võrgu segmenteerimine ja logistika
**Situatsioon:** Transpordifirma kontoriarvuti nakatus, kuid logistikaserverid jäid terveks tänu segmenteerimisele.
**Ülesanne:** Joonista või kirjelda lihtsustatud võrguskeem, kus on näha "Kontorivõrk", "Logistikaserverite võrk" ja nende vahel asuv "Tulemüür". Selgita, milline reegel tulemüüris (Luba/Keela) takistas pahavara liikumist punktist A punkti B.



[Image of network segmentation diagram firewall between vlan]


#### 4. Ülesanne: Credential Stuffing rünnak
**Situatsioon:** Hotelliketi töötaja kasutas sama parooli voogedastuses ja töö juures, mis viis kliendiandmete varguseni.
**Ülesanne:** Analüüsi ründaja teekonda. Kirjelda samm-sammult, kuidas "Credential Stuffing" rünnak töötab, alustades andmelekkest kolmandas osapoole teenuses kuni hotelli serverisse sisenemiseni. Kuidas oleks paroolihaldur selle ahela katkestanud?

#### 5. Ülesanne: MFA ja andmepüügi (Phishing) tõrjumine
**Situatsioon:** IT-juhi parool varastati, kuid rünnak ebaõnnestus tänu füüsilisele turvavõtmele.
**Ülesanne:** Määra kindlaks autentimistegurid (factors), mis olid selles näites mängus. Kasutades kolme kategooriat (*Mida sa tead, Mida sa omad, Kes sa oled*), liigita IT-juhi parool ja tema U2F võti. Miks ründajal ei õnnestunud sisse logida, kuigi tal oli parool?

#### 6. Ülesanne: 3-2-1 Varundusreegel ja lunavara
**Situatsioon:** Väike tootmisettevõte taastas andmed, sest varukoopiad olid võrgust lahti ühendatud.
**Ülesanne:** Hinda ettevõtte tegevust 3-2-1 reegli valguses. Selgita, mis oleks juhtunud stsenaariumis, kui ettevõte oleks hoidnud varukoopiaid ainult võrku ühendatud (mapped drive) kõvakettal. Miks lunavara just võrguühendusi otsib?

#### 7. Ülesanne: Turvateadlikkuse tuvastamine
**Situatsioon:** Logistikafirma töötajad said "IT-osakonnalt" kirja, mis nõudis parooli, kuid enamik ei avanud seda.
**Ülesanne:** Koosta nimekiri kolmest (3) tunnusest, mis võisid töötajatele reeta, et tegemist on õngitsuskirjaga (tugine üldistele teadmistele ja tekstile), arvestades, et saatja aadress oli kahtlane.

#### 8. Ülesanne: Ligipääsude audit (Offboarding)
**Situatsioon:** Endine süsteemiadministraator varastas andmeid, sest tema konto jäi avatuks.
**Ülesanne:** Koosta 5-punktiline kontrollnimekiri (checklist) HR-ile ja IT-osakonnale pealkirjaga "Töötaja lahkumise protseduur", mis oleks selle intsidendi 100% välistanud.

#### 9. Ülesanne: Andmekandja krüpteerimine
**Situatsioon:** Riigihangete andmed lekkisid kaotatud mälupulga tõttu.
**Ülesanne:** Võrdle kahte stsenaariumi: A) Kaotatud mälupulk on krüpteerimata (nagu tekstis) vs B) Kaotatud mälupulk on krüpteeritud AES-256 standardiga. Kirjelda, mida näeb leidja, kui ta ühendab stsenaariumi B puhul mälupulga oma arvutisse.

#### 10. Ülesanne: Külalisvõrgu eraldamine
**Situatsioon:** Ründaja kasutas Guest Wi-Fi-d, et skaneerida sisevõrku, kuid blokeeriti.
**Ülesanne:** Selgita tehniliselt, miks peaks "Guest Wi-Fi" olema täiesti eraldatud VLAN (Virtual Local Area Network). Mis on risk, kui töötajad ühendavad oma tööarvutid mugavuse tõttu Külalisvõrku?

#### 11. Ülesanne: Andmete klassifitseerimine
**Situatsioon:** Töötajad hoidsid konfidentsiaalseid andmeid avalikus pilves, sest puudus poliitika.
**Ülesanne:** Loo lihtne tabel kahe veeruga: "Avalik info" ja "Konfidentsiaalne info". Paiguta tekstis mainitud "turundusmaterjalid" ja "kliendiandmed" õigetesse lahtritesse. Lisa ise kummassegi veergu veel üks näide, mis sobiks tüüpilisse ettevõttesse.

#### 12. Ülesanne: Intsidendi kommunikatsioon
**Situatsioon:** Valitsusasutus suutis DDoS rünnaku ajal info kiiresti liikuma panna tänu plaanile.
**Ülesanne:** Analüüsi kommunikatsiooni tähtsust. Miks on tekstis mainitud "6 tunni jooksul teavitamine" oluline maine kahjustamise seisukohalt? Mis juhtub "narratiiviga", kui asutus vaikib 24 tundi, aga kliendid juba märkavad teenuse katkestust?

---

### Näidislahendus

Valisin lahendamiseks **Ülesande nr 4: Credential Stuffing rünnak**.

#### Ülesanne:
Analüüsi ründaja teekonda. Kirjelda samm-sammult, kuidas "Credential Stuffing" rünnak töötab, alustades andmelekkest kolmandas osapoole teenuses kuni hotelli serverisse sisenemiseni. Kuidas oleks paroolihaldur selle ahela katkestanud?

#### Lahendus:

**1. Ründaja teekonna (Attack Chain) analüüs:**

* **Samm 1: Algne leke.** Töötaja kasutab parooli (nimetame seda `Parool123`) ja e-posti aadressi oma isiklikus voogedastusplatvormis (nt Netflix, Spotify vms). See platvorm langeb rünnaku alla ja nende andmebaas varastatakse.
* **Samm 2: Andmebaasi avalikustamine.** Ründajad (või andmete ostjad Dark Webis) saavad kätte nimekirja kasutajatest ja nende paroolidest (`email@hotell.ee` + `Parool123`).
* **Samm 3: Automatiseeritud proovimine (Stuffing).** Ründaja kasutab spetsiaalset tarkvara (bot), mis võtab selle lekkinud nimekirja ja proovib automaatselt sisse logida sadadesse teistesse teenustesse, sealhulgas hotelli broneerimissüsteemi või töötaja tööposti.
* **Samm 4: Edukas sisenemine.** Kuna töötaja rikkus küberhügieeni reeglit ja kasutas sama parooli (`Parool123`) ka oma töökeskkonnas, saab ründaja süsteemi sisse ("Match found").
* **Samm 5: Kahju tekitamine.** Ründaja on nüüd hotelli süsteemis sees ja pääseb ligi klientide andmetele.



**2. Kuidas paroolihaldur oleks ahela katkestanud:**

Kui töötaja oleks kasutanud paroolihaldurit (nt Bitwarden, 1Password):

* Voogedastusteenuse parool oleks olnud unikaalne ja keeruline (nt `Xy9#mP2!zQ`).
* Töösüsteemi parool oleks olnud täiesti teistsugune ja unikaalne (nt `Lm7$nR4&wK`).
* **Tulemus:** Kui voogedastusteenuse parool `Xy9#mP2!zQ` oleks lekkinud ja ründaja oleks proovinud sellega hotelli süsteemi siseneda, oleks vastuseks olnud "Vale parool". Rünnak oleks ebaõnnestunud sammus nr 4.

---

**Kas soovid, et koostaksin detailse lahenduse ka mõnele teisele ülesandele, näiteks võrgu segmenteerimise või varunduse teemal?**
