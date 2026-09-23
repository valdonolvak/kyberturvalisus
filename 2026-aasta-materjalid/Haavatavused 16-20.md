Jätkan nüüd peatükkidega **16–20** samas ülesehituses. Hoian siin samuti põhimõtet, et iga haavatavuse juures selgitatakse kõigepealt mõiste ja tehniline mehhanism ning alles seejärel tuuakse konkreetsed CVE-näited. CVE-de puhul eristan ka seda, kas allikas kirjeldab tegelikku ärakasutamist või ainult haavatavuse olemasolu.

# 16. Network Service / Protocol Vulnerabilities ehk võrguteenuste ja -protokollide haavatavused

Operatsioonisüsteem ei tööta tavaliselt isoleeritult, vaid suhtleb teiste arvutite, serverite, ruuterite, printerite, pilveteenuste ja kasutajaseadmetega. Selleks kasutatakse võrguteenuseid (network services) ja võrguprotokolle (network protocols). Võrguteenus on operatsioonisüsteemis töötav programm või protsess, mis ootab võrgust ühendusi ja pakub mõnda funktsiooni. Näiteks Windowsi SMB võimaldab failide ja printerite jagamist, SSH võimaldab Linuxi serveri kaugjuhtimist ning veebiteenused kasutavad HTTP/HTTPS-protokolli. Kui selline teenus sisaldab programmeerimisviga, võib ründaja saada võimaluse saata sellele spetsiaalselt koostatud andmeid. Probleem võib seisneda näiteks vigases pakettide töötlemises, ebapiisavas autentimises või vigases olekuhalduse loogikas. Eriti ohtlikud on haavatavused teenustes, mis on Internetist otse kättesaadavad. Sellisel juhul ei pruugi ründaja esmalt vajada kasutajakontot ega füüsilist juurdepääsu arvutile. Mõnel juhul võib võrgu kaudu saadetud päring põhjustada teenuse krahhi, teisel juhul aga koodi käivitamise. Võrguteenuse haavatavus võib seetõttu olla ründeahela esimene lüli. Pärast esmast kompromiteerimist võib ründaja liikuda edasi õiguste suurendamise, tunnuste varastamise või teiste süsteemide kompromiteerimise juurde.

## Kuidas võrguteenuse haavatavus tehniliselt tekib?

Võrguteenus võtab vastu andmeid, mille sisu ei ole tavaliselt täielikult teenuse enda kontrolli all. Näiteks SMB-server võtab vastu SMB-sõnumeid, SSH-server SSH-protokolli sõnumeid ning veebiserver HTTP-päringuid. Teenus peab need andmed parsima (parse), kontrollima ja töötlema.

Kui kontrollimine on puudulik, võib ründaja saata väärtuse, mida programmeerija ei arvestanud.

Näiteks võib rakendus eeldada, et saabub 100 baiti andmeid, kuid tegelikult saabub 10 000 baiti. Kui pikkust ei kontrollita, võib tekkida mälukorruptsioon. Teisel juhul võib teenus eeldada, et klient on pärast teatud sõnumit autenditud, kuigi autentimise olekut ei kontrollita korrektselt.

Lihtsustatud kujul:

```text
Ründaja
   |
   | spetsiaalselt koostatud võrgupakett
   v
+-----------------------+
| Võrguteenus           |
| SMB / SSH / HTTP /... |
+-----------------------+
          |
          v
   Vigane sisendi
   töötlemine
          |
     +----+----+
     |         |
     v         v
   Crash     Koodi
             käivitamine
                 |
                 v
          Süsteemi kompromiss
```

Võrguprotokolli viga võib olla ka loogiline. Näiteks võib autentimisprotsess lubada ründajal mööduda mõnest kontrollsammust. Seetõttu ei tähenda võrguteenuse haavatavus alati mälukorruptsiooni.

## Mida ründaja pärast võrguteenuse haavatavuse kasutamist teha võib?

Kõige otsesem mõju on teenuse töö häirimine. Kui teenus jookseb kokku, võib süsteem muutuda kasutajatele kättesaamatuks. RCE (Remote Code Execution) tüüpi haavatavuse korral võib ründaja aga saada võimaluse käivitada ohvri süsteemis enda valitud käske või programmi.

Oluline on see, **milliste õigustega teenus töötab**. Kui teenus töötab piiratud kasutajana, on kahju väiksem kui juhul, kui teenus töötab süsteemiadministraatori või `SYSTEM`-taseme õigustega.

Pärast võrguteenuse kompromiteerimist võib ründaja proovida leida teisi süsteeme, mida ohvrilt võrgus näha saab. Seda nimetatakse külgliikumiseks (lateral movement). Organisatsioonis võib ühe serveri kompromiteerimine seetõttu viia teiste serverite ja kasutajakontode kompromiteerimiseni.

### Windows

**CVE-2020-0796 – SMBv3 / SMBGhost**

* **Komponent:** Windows SMBv3.
* **Tehniline probleem:** SMBv3 pakettide töötlemisega seotud mälukorruptsioon.
* **Eeltingimus:** mõjutatud Windowsi versioon ja haavatav SMBv3 konfiguratsioon.
* **Mõju:** spetsiaalselt koostatud SMB-andmed võisid viia koodi käivitamiseni või teenuse töö häirimiseni.
* **Ärakasutamine:** haavatavus oli laialdaselt käsitletud kriitilise võrguteenuse riskina; CISA KEV-i staatuse kontroll tuleb teha konkreetse süsteemi ja kuupäeva suhtes.
* **Parandus:** Microsofti turvauuendus ja SMB kokkupuutepinna vähendamine.

**CVE-2024-38063 – Windows TCP/IP**

* **Komponent:** Windows TCP/IP.
* **Tehniline probleem:** IPv6 võrgupakettide töötlemise haavatavus.
* **Eeltingimus:** mõjutatud Windowsi versioon ning võrguühendus, mille kaudu saab süsteemile IPv6 liiklust saata.
* **Mõju:** Microsoft klassifitseeris selle kriitilise taseme kaugkäivitamise riskina.
* **Oluline mõte:** ründaja ei pea tingimata esmalt Windowsisse sisse logima, kui haavatav võrgukomponent töötleb pahatahtlikku liiklust.
* **Parandus:** Windowsi turvauuendused ja vajadusel IPv6 kokkupuutepinna piiramine.

Microsofti Security Update Guide on Windowsi konkreetsete turvauuenduste ja mõjutatud toodete kontrollimise põhireferents. ([Microsoft Security Response Center][1])

### Linux

**CVE-2024-6387 – OpenSSH „regreSSHion“**

* **Komponent:** OpenSSH server (`sshd`).
* **Tehniline probleem:** signaalikäitlemisega seotud race condition ehk ajastusvõistlus.
* **Eeltingimus:** haavatav OpenSSH versioon ning võrgu kaudu kättesaadav SSH-teenus.
* **Mõju:** teatud tingimustel võib ründaja saavutada koodi käivitamise sshd protsessi kontekstis.
* **Ärakasutamine:** haavatavus sai 2024. aastal ulatuslikku turvaseire tähelepanu; konkreetse süsteemi puhul tuleb hinnata versiooni ja logisid.
* **Parandus:** OpenSSH uuendamine ning SSH ligipääsu piiramine.

**CVE-2023-38408 – OpenSSH agent forwarding**

* **Komponent:** OpenSSH klient ja SSH-agentiga seotud funktsioonid.
* **Tehniline probleem:** ebapiisavalt kontrollitud laaditavate komponentide kasutamine teatud agent-forwarding olukordades.
* **Eeltingimus:** ründaja peab jõudma sobivasse SSH-seanssi ning ohver peab kasutama mõjutatud funktsiooni.
* **Mõju:** teatud tingimustel võis tekkida võimalus koodi käivitamiseks kliendi kontekstis.
* **Parandus:** OpenSSH uuendamine ja agent-forwardingu mittevajalikul juhul keelamine.

Ubuntu Security Notices võimaldab kontrollida, millistes Ubuntu väljalasetes konkreetne Linuxi paketi või kerneli CVE on parandatud. ([Ubuntu][2])

### macOS

**CVE-2024-23222 – WebKit**

* **Komponent:** WebKit.
* **Tehniline probleem:** veebisisu töötlemisega seotud type confusion ehk tüübi segiajamise viga.
* **Eeltingimus:** ründaja peab suutma ohvri seadmes töödelda spetsiaalselt koostatud veebisisu.
* **Mõju:** Apple kirjeldas võimalust saavutada pahatahtliku veebisisu töötlemisel suvalise koodi käivitamine.
* **Ärakasutamine:** Apple märkis vastavas turvauuenduses, et teadis raportist võimaliku aktiivse ärakasutamise kohta.
* **Parandus:** macOS-i uuendamine. ([Apple Support][3])

**CVE-2023-38545 – curl**

* **Komponent:** macOS-i kaasas olev `curl`/libcurl.
* **Tehniline probleem:** SOCKS5 hostname töötlemisega seotud heap buffer overflow.
* **Eeltingimus:** mõjutatud `curl` versioon ning vastava funktsiooni kasutamine.
* **Mõju:** võib põhjustada mälukorruptsiooni.
* **Oluline:** tegemist on avatud lähtekoodiga komponendiga, mida macOS sisaldab, mitte Apple'i enda võrguprotokolli rakendusega.
* **Parandus:** süsteemi või vastava `curl` komponendi uuendamine. Apple dokumenteerib selle CVE oma Monterey turvauuenduses. ([Apple Support][3])

### Android

Androidi puhul on võrguteenuste haavatavused sageli seotud süsteemikomponentide, Wi-Fi, Bluetoothi või kerneli võrgukomponentidega. Androidi turvabülletäänid eristavad näiteks RCE-d, õiguste suurendamist ja teabe avalikustumist. ([Android Open Source Project][4])

**CVE-2024-43091**

* **Komponent:** Android System.
* **Tehniline probleem:** süsteemikomponendi haavatavus.
* **Eeltingimus:** mõjutatud Androidi versioon.
* **Mõju:** Google klassifitseeris selle RCE-haavatavuseks.
* **Parandus:** vähemalt 2024-11-05 turvapaigaga seade. ([Android Open Source Project][4])

**CVE-2024-43083**

* **Komponent:** Androidi Wi-Fi süsteemikomponent.
* **Tehniline probleem:** Wi-Fi komponendi haavatavus.
* **Eeltingimus:** mõjutatud süsteem ja Wi-Fi funktsionaalsuse kasutamine.
* **Mõju:** Androidi 2024. aasta novembri bülletäänis on see liigitatud DoS-haavatavuseks.
* **Parandus:** vastav Androidi turvauuendus. ([Android Open Source Project][4])

### iOS

**CVE-2024-23222 – WebKit**

* **Komponent:** WebKit.
* **Tehniline probleem:** type confusion veebisisu töötlemisel.
* **Eeltingimus:** pahatahtlikult koostatud veebisisu töötlemine.
* **Mõju:** suvalise koodi käivitamine.
* **Ärakasutamise kohta:** Apple märkis seotud väljaande puhul võimaliku aktiivse ärakasutamise raporti.
* **Parandus:** iOS-i turvauuendus. ([Apple Support][3])

**CVE-2024-23218 – Kernel**

* **Komponent:** iOS kernel.
* **Tehniline probleem:** mäluhalduse viga.
* **Eeltingimus:** pahatahtlik rakendus või muu kohalik ründevõimalus.
* **Mõju:** rakendus võis saada koodi käivitamise võimaluse kerneli õigustes.
* **Parandus:** iOS-i turvauuendus. ([Apple Support][5])

## Kuidas riski vähendada?

Kõige olulisem kaitse on mittevajalike võrguteenuste sulgemine. Kui server ei vaja Internetist SSH-d, SMB-d või muud teenust, ei ole mõistlik seda avalikult kättesaadavaks teha. Kasutada tuleks tulemüüri (firewall), mis piirab lubatud lähteaadresse ja porte. Võrguteenused tuleb regulaarselt paigata. SSH puhul tuleb piirata autentimisviise ja võimalusel kasutada võtmetel põhinevat autentimist. Serverite puhul on kasulik eraldada haldusvõrk tavalisest kasutajavõrgust. Logimine ja võrgujälgimine võimaldavad tuvastada ebatavalisi ühendusi. Eriti oluline on jälgida Internetist otse kättesaadavaid teenuseid.

---

# 17. Kernel and Driver Vulnerabilities ehk kerneli ja draiverite haavatavused

Kernel ehk tuum on operatsioonisüsteemi keskne osa, mis kontrollib riistvara, mälu, protsesse, seadmeid ja paljusid süsteemiteenuseid. Tavaline kasutajaprogramm ei tohiks saada vabalt lugeda ega muuta kerneli mälu. Kernel töötab kõrgemate õigustega kui tavalised rakendused. Draiver (driver) on tarkvarakomponent, mis võimaldab operatsioonisüsteemil konkreetse riistvaraga suhelda. Näiteks vajavad Windows, Linux ja macOS eraldi draivereid graafikakaardi, võrguadapteri, USB-seadme ja paljude teiste komponentide jaoks. Kerneli või draiveri viga on eriti oluline, sest vigane kood töötab sageli väga kõrgete õigustega. Kui ründaja suudab sellises komponendis mälukorruptsiooni tekitada, võib ta saada õigused, mida tavalisel rakendusel ei ole. Selline haavatavus võib võimaldada õiguste suurendamist (privilege escalation), andmete vargust või süsteemi täielikku kompromiteerimist. Draiverid on keerulised, sest nad töötlevad sageli otse riistvara või madala taseme andmeid. Seetõttu võivad nende vead olla raskemini avastatavad ja parandatavad kui tavalise rakenduse vead.

## Kuidas kerneli või draiveri haavatavus tehniliselt tekib?

Kernel ja draiverid töötavad sageli privilegeeritud režiimis. See tähendab, et neil on ligipääs süsteemiressurssidele, millele tavakasutaja rakendusel ligipääsu ei ole.

Näiteks võib draiver võtta kasutajaprogrammilt vastu andmestruktuuri. Kui draiver ei kontrolli selle struktuuri pikkust või sisu, võib kasutaja mõjutada kerneli tööd.

```text
Kasutaja rakendus
       |
       | süsteemikutse / IOCTL
       v
+-------------------+
| Draiver / kernel  |
+-------------------+
       |
       | puudulik kontroll
       v
  Mälukorruptsioon
       |
       v
Kernelitaseme kood
       |
       v
Täielikum kontroll süsteemi üle
```

IOCTL (Input/Output Control) on mehhanism, mille kaudu kasutajaruumi programm saab teatud seadme või draiveriga suhelda. Kui draiver käsitleb IOCTL-i kaudu saadud andmeid valesti, võib sellest tekkida turvaprobleem.

## Mida ründaja pärast kerneli või draiveri haavatavuse kasutamist teha võib?

Kõige olulisem risk on õiguste suurendamine. Näiteks võib piiratud kasutaja või pahatahtlik rakendus püüda liikuda kerneli konteksti.

Kerneli kompromiteerimine võib tähendada, et tavapärased rakenduste turvamehhanismid muutuvad vähem tõhusaks. Ründaja võib proovida muuta protsesside õigusi, lugeda kaitstud mälu või manipuleerida süsteemiteenustega.

Mobiilseadmetes on kernel eriti oluline, sest rakendused töötavad tavaliselt liivakastis (sandbox). Kui kernel kompromiteeritakse, võib ründaja proovida sellest liivakastist välja pääseda.

### Windows

**CVE-2024-21338 – Windows Kernel**

* **Komponent:** Windows Kernel.
* **Tehniline probleem:** kerneli tasemel õiguste suurendamise haavatavus.
* **Eeltingimus:** ründajal peab olema lokaalne võimalus mõjutatud süsteemis koodi käivitada.
* **Mõju:** madalama õigusega protsess võib püüda saavutada kõrgemaid õigusi.
* **Parandus:** Windowsi turvauuendus.

**CVE-2024-38042 – Windows Hyper-V**

* **Komponent:** Hyper-V.
* **Tehniline probleem:** virtualiseerimiskihi haavatavus.
* **Eeltingimus:** mõjutatud Hyper-V keskkond ja sobiv lokaalne/virtuaalmasina juurdepääs.
* **Mõju:** virtualiseerimiskihi haavatavus võib mõjutada hosti või teiste VM-ide isolatsiooni.
* **Parandus:** Microsofti turvauuendus ning virtuaalmasinate ja hosti eraldamine.

### Linux

**CVE-2024-1086 – Linux kernel netfilter**

* **Komponent:** Linuxi kerneli netfilter.
* **Tehniline probleem:** mäluhalduse viga.
* **Eeltingimus:** lokaalne ligipääs süsteemile.
* **Mõju:** õiguste suurendamine kernelitasemele.
* **Parandus:** Linuxi kerneli uuendamine.

**CVE-2022-0185 – Linux kernel filesystem**

* **Komponent:** Linuxi kernel ja filesystem-kood.
* **Tehniline probleem:** heap buffer overflow.
* **Eeltingimus:** sobiv lokaalne kasutajaõigus ja haavatav kernel.
* **Mõju:** võib võimaldada kohaliku kasutaja õiguste suurendamist.
* **Parandus:** kerneli uuendamine.

Ubuntu avaldab kerneli haavatavuste kohta eraldi turvateated ning näiteks 2025. aasta teates kirjeldatakse olukordi, kus süsteemi võis põhjustada krahhi või võimaldada programmide käitamist administraatori õigustes. ([Ubuntu][6])

### macOS

**CVE-2024-23234 – Intel Graphics Driver**

* **Komponent:** Intel Graphics Driver.
* **Tehniline probleem:** out-of-bounds write ehk kirjutamine lubatud mälupiirkonnast väljapoole.
* **Eeltingimus:** pahatahtlik rakendus peab olema süsteemis käivitatud.
* **Mõju:** Apple kirjeldas võimalust käivitada koodi kerneli õigustes.
* **Parandus:** macOS-i turvauuendus. ([Apple Support][7])

**CVE-2024-23265 – Kernel**

* **Komponent:** macOS kernel.
* **Tehniline probleem:** mälukorruptsiooniga seotud viga.
* **Mõju:** rakendus võis põhjustada süsteemi ebastabiilsust või kirjutada kerneli mälu.
* **Parandus:** Apple'i turvauuendus. ([Apple Support][8])

### Android

**CVE-2024-53104 – Linux kernel UVC**

* **Komponent:** Androidi Linux kernel, UVC (USB Video Class).
* **Tehniline probleem:** kerneli taseme haavatavus.
* **Eeltingimus:** mõjutatud seadme kernel ja vastava riistvara kasutamine.
* **Mõju:** Androidi turvabülletään klassifitseerib selle õiguste suurendamise probleemina.
* **Parandus:** 2025-02-05 või uuem asjakohane turvapaik. ([Android Open Source Project][9])

**CVE-2025-0088 – Linux kernel mremap**

* **Komponent:** Linuxi kernel, `mremap`.
* **Tehniline probleem:** mälupiirkondade ümberpaigutamisega seotud kerneli viga.
* **Mõju:** Androidi bülletäänis liigitatud EoP-haavatavuseks.
* **Parandus:** vastav Androidi kerneli turvauuendus. ([Android Open Source Project][9])

### iOS

**CVE-2024-27815 – Kernel**

* **Komponent:** iOS kernel.
* **Tehniline probleem:** out-of-bounds write.
* **Mõju:** rakendus võis saada koodi käivitamise võimaluse kerneli õigustes.
* **Parandus:** parandatud iOS-i turvauuendusega. ([Apple Support][10])

**CVE-2021-1782 – Kernel**

* **Komponent:** iOS kernel.
* **Tehniline probleem:** race condition ehk olukord, kus programmi tulemus sõltub mitme tegevuse täpsest ajastusest.
* **Mõju:** pahatahtlik rakendus võis suurendada enda õigusi.
* **Ärakasutamine:** Apple teatas, et oli teadlik võimalikust aktiivsest ärakasutamisest.
* **Parandus:** iOS 14.4 ja vastavad uuendused. ([Android Open Source Project][11])

## Kuidas riski vähendada?

Kerneli ja draiverite puhul on kõige olulisem hoida operatsioonisüsteem ajakohasena. Draivereid ei tohiks paigaldada juhuslikest allikatest. Organisatsioonis tuleks kasutada tootja või operatsioonisüsteemi usaldusväärseid draiveripakette. Mittevajalikud seadmed ja draiverid tuleks võimalusel eemaldada või keelata. Serverites võib vähendada ründepinda, eemaldades mittevajalikud kernelimoodulid ja teenused. Virtualiseerimiskeskkondades tuleb hosti ja külalisoperatsioonisüsteemide turvauuendusi käsitleda eraldi. Kerneli taseme intsidentide uurimisel on oluline säilitada logid ja võimalusel kasutada keskset monitooringut.

---

# 18. File and Media Parser Vulnerabilities ehk faili- ja meediumiparserite haavatavused

Operatsioonisüsteemid töötlevad iga päev tohutul hulgal faile. Nende hulka kuuluvad pildid, videod, helifailid, PDF-dokumendid, fondid, arhiivid ja veebisisu. Selleks kasutatakse parsereid (parser), mille ülesanne on teisendada faili sees olevad andmed programmi jaoks arusaadavaks struktuuriks. Näiteks ImageIO töötleb pilte ning fontide parserid töötlevad fondifaile. Parser peab suutma käsitleda väga palju erinevaid sisendeid. Kui parser eeldab, et faili formaat on korrektne, kuid ründaja koostab tahtlikult vigase faili, võib tekkida turvaprobleem. Sellised vead võivad põhjustada puhvri ületäite, out-of-bounds ligipääsu, use-after-free olukorra või integer overflow'i. Ründaja võib faili saata e-posti teel, panna veebilehele või anda selle kasutajale allalaadimiseks. Mõnel juhul piisab faili eelvaate kuvamisest, sest operatsioonisüsteem alustab faili töötlemist automaatselt. Seetõttu ei pea kasutaja alati faili teadlikult programmis avama. Meediumiparserite haavatavused on olnud oluline ründepinna osa nii arvutites kui ka mobiilseadmetes.

## Kuidas faili- või meediumiparseri haavatavus tehniliselt tekib?

Parser loeb faili järjest ja teisendab selle sisemised andmed programmisisesteks objektideks.

```text
Pilt / PDF / font / video
          |
          v
     +---------+
     | Parser  |
     +---------+
          |
          v
  Kontrollimata väärtus
          |
    +-----+------+
    |            |
    v            v
Mälukorruptsioon  Crash
    |
    v
Koodi käivitamise võimalus
```

Näiteks võib fail öelda, et järgmine andmeplokk on 500 baiti pikk, kuid tegelikult sisaldab parserile antud struktuur vähem andmeid. Kui programm ei kontrolli piire, võib ta lugeda või kirjutada mälust valesse kohta.

Teine näide on integer overflow. Kui programmi arvutus jõuab liiga suure arvuni, võib arv muutuda ootamatult väikeseks. Selle tulemusena võib programm eraldada liiga väikese mälupiirkonna ja kirjutada sinna liiga palju andmeid.

## Mida ründaja pärast parseri haavatavuse kasutamist teha võib?

Parseri haavatavus võib anda võimaluse mõjutada selle programmi mäluruumi. Kui parser töötab piiratud kasutajaõigustega, on ründaja esmane positsioon samuti piiratud.

Kui parser töötab aga kõrgete õigustega süsteemikomponendina, võib mõju olla palju suurem. Mobiilseadmetes võivad parserid töötada taustal ja töödelda kasutajale nähtamatult saabunud meediumisisu.

Seetõttu on eriti tähtis, et meediumifailide töötlemisel kasutatakse sisendite valideerimist, mälupiirangute kontrolli ja sandbox'i.

### Windows

**CVE-2023-4863 – libwebp**

* **Komponent:** WebP-failide töötlemiseks kasutatav libwebp, mida kasutavad mitmed Windowsis töötavad rakendused.
* **Tehniline probleem:** heap buffer overflow.
* **Eeltingimus:** pahatahtliku WebP-sisu töötlemine.
* **Mõju:** out-of-bounds kirjutamine ja potentsiaalne koodi käivitamine.
* **Ärakasutamine:** CISA KEV sisaldab seda haavatavust.
* **Parandus:** libwebp-i sisaldava rakenduse uuendamine. ([NVD][12])

**CVE-2024-38178 – MSHTML**

* **Komponent:** Windows MSHTML.
* **Tehniline probleem:** veebisisu töötlemisega seotud mäluturbe probleem.
* **Eeltingimus:** kasutaja peab töötlema spetsiaalselt koostatud sisu.
* **Mõju:** võib võimaldada koodi käivitamist.
* **Parandus:** Microsofti turvauuendus.

### Linux

**CVE-2022-44268 – ImageMagick**

* **Komponent:** ImageMagick.
* **Tehniline probleem:** spetsiaalselt koostatud PNG-faili töötlemine võis põhjustada tundliku protsessimälu avalikustumist.
* **Eeltingimus:** haavatavat ImageMagick-versiooni kasutav süsteem peab faili töötlema.
* **Mõju:** infolekke risk.
* **Parandus:** ImageMagick uuendamine.

**CVE-2023-4863 – libwebp**

* **Komponent:** libwebp.
* **Tehniline probleem:** heap buffer overflow.
* **Eeltingimus:** pahatahtliku WebP-faili töötlemine.
* **Mõju:** mälukorruptsioon ja potentsiaalne koodi käivitamine.
* **Ärakasutamine:** CISA KEV-is.
* **Parandus:** libwebp või seda kasutava rakenduse uuendamine. ([NVD][12])

### macOS

**CVE-2024-23286 – ImageIO**

* **Komponent:** macOS ImageIO.
* **Tehniline probleem:** buffer overflow.
* **Eeltingimus:** pahatahtlikult koostatud pildi töötlemine.
* **Mõju:** Apple kirjeldas võimalust saavutada suvalise koodi käivitamine.
* **Parandus:** macOS-i turvauuendus. ([Apple Support][7])

**CVE-2024-54499 – ImageIO**

* **Komponent:** ImageIO.
* **Tehniline probleem:** use-after-free.
* **Eeltingimus:** pahatahtlikult koostatud pildi töötlemine.
* **Mõju:** Apple kirjeldas võimalust suvalise koodi käivitamiseks.
* **Parandus:** macOS Sequoia turvauuendus. ([Apple Support][13])

### Android

**CVE-2024-0039**

* **Komponent:** Android System.
* **Tehniline probleem:** süsteemikomponendi haavatavus, mille mõju klassifitseeriti RCE-na.
* **Eeltingimus:** mõjutatud Androidi versioon ja vastava sisendi töötlemine.
* **Mõju:** kaugelt algatatav koodi käivitamine.
* **Parandus:** Androidi vastav turvapaik. ([Android Open Source Project][14])

**CVE-2024-43089**

* **Komponent:** Android MediaProvider.
* **Tehniline probleem:** meediumi töötlemisega seotud turvaviga.
* **Mõju:** Androidi turvabülletään klassifitseeris selle teabe avalikustamise probleemina.
* **Parandus:** 2024-11-05 või uuem asjakohane turvapaik. ([Android Open Source Project][4])

### iOS

**CVE-2020-27930 – FontParser**

* **Komponent:** iOS FontParser.
* **Tehniline probleem:** out-of-bounds write.
* **Eeltingimus:** pahatahtliku fondi töötlemine.
* **Mõju:** suvalise koodi käivitamise võimalus.
* **Ärakasutamine:** Apple teatas, et teadis aktiivsest ärakasutamisest.
* **Parandus:** iOS-i turvauuendus.

**CVE-2020-10002 – ImageIO**

* **Komponent:** iOS ImageIO.
* **Tehniline probleem:** out-of-bounds read.
* **Eeltingimus:** pahatahtlikult koostatud pildi töötlemine.
* **Mõju:** Apple kirjeldas võimalust suvalise koodi käivitamiseks.
* **Parandus:** täiustatud sisendi valideerimine.

Apple'i iOS 14.2 turvauuenduses on mõlemad näited dokumenteeritud koos paranduse kirjeldusega.

## Kuidas riski vähendada?

Faile tuleks töödelda ajakohase tarkvaraga. Eriti oluline on uuendada veebibrausereid, PDF-lugejaid, pildi- ja videokoodekeid ning meediumiteeke. E-posti manuste automaatset töötlemist tuleks võimalusel piirata. Rakenduste sandbox vähendab kahju juhul, kui parser kompromiteeritakse. Serverites tuleks vältida tundmatute failide töötlemist kõrgete õigustega protsessides. Failivormingu valideerimine peab toimuma enne faili tegelikku töötlemist. Turvaseire peaks hõlmama ka ebatavalisi rakenduste krahhe, sest korduvad parserikrahhid võivad viidata pahatahtlikule sisendile.

---

# 19. Software Supply-Chain Vulnerabilities ehk tarkvara tarneahela haavatavused

Tarkvara tarneahel (software supply chain) tähendab kõiki komponente, tööriistu, teeke, pakette, uuendusi ja tootmisprotsesse, millest lõplik tarkvara koosneb. Tänapäeva operatsioonisüsteem ei koosne ainult tootja enda kirjutatud koodist. Windows, Linux, macOS, Android ja iOS sisaldavad või kasutavad suurt hulka kolmandate osapoolte komponente. Näiteks võib operatsioonisüsteem sisaldada avatud lähtekoodiga teeki, mida kasutavad omakorda kümned teised programmid. Kui sellesse ahelasse satub haavatav või pahatahtlik komponent, võivad mõjutatud olla tuhanded süsteemid. Tarneahela rünnak erineb tavalisest haavatavusest selle poolest, et ohver võib kasutada täiesti tavapärast ja usaldusväärsest allikast saadud tarkvara. Probleem võib tekkida enne, kui tarkvara jõuab lõppkasutajani. Samuti võib ründaja kompromiteerida tarkvara ehitamise või allkirjastamise protsessi. Sellisel juhul võib pahatahtlik komponent jõuda kasutajani koos näiliselt ametliku tarkvarauuendusega. Tarneahela turvalisus nõuab seetõttu tähelepanu mitte ainult lõppseadmele, vaid ka tarkvara tootjale ja tema sõltuvustele. See on üks põhjusi, miks tarkvara päritolu, digitaalallkirjad ja sõltuvuste haldamine on olulised.

## Kuidas tarkvara tarneahela haavatavus tehniliselt tekib?

Tarkvara võib olla üles ehitatud paljudest sõltuvustest.

```text
Rakendus
   |
   +---- Teek A
   |
   +---- Teek B
   |        |
   |        +---- Teek C
   |
   +---- Pakett D
            |
            v
       Haavatav komponent
            |
            v
       Lõplik tarkvara
            |
            v
      Kasutaja süsteem
```

Probleem võib tekkida näiteks juhul, kui arendaja kasutab teeki, mille turvaviga pole veel avastatud. Veel keerulisem olukord tekib siis, kui ründaja suudab ise mõjutada paketti või tarkvara ehitamise protsessi.

Tarneahela rünnak võib seega toimuda mitmel tasandil:

```text
Lähtekood
   ↓
Sõltuvus / teek
   ↓
Build-system
   ↓
Pakett
   ↓
Digitaalallkiri
   ↓
Uuendusserver
   ↓
Lõppseade
```

Kui üks usaldusahela osa on kompromiteeritud, võib pahatahtlik kood liikuda järgmisele tasemele.

## Mida ründaja pärast tarneahela kompromiteerimist teha võib?

Tarneahela rünnaku suur eelis ründaja jaoks on ulatus. Ühe komponendi kompromiteerimisega võib olla võimalik mõjutada väga paljusid organisatsioone.

Näiteks võib pahatahtlik komponent töötada süsteemis sama konto või teenuse õigustega nagu originaalne tarkvara. Kui kompromiteeritud tarkvara töötab administraatoriõigustes, võib selle mõju olla väga suur.

Tarneahela ründed võivad olla ka raskesti avastatavad, sest süsteemis töötab näiliselt seaduslikult allkirjastatud tarkvara. Seetõttu ei piisa alati ainult faili digiallkirja kontrollimisest.

### Windows

**CVE-2020-10148 – SolarWinds Orion**

* **Komponent:** SolarWinds Orion.
* **Seos Windowsiga:** Orionit kasutati laialdaselt Windowsi serverikeskkondades.
* **Tehniline probleem:** autentimise möödahiilimine Orion API-s.
* **Mõju:** ründaja võis API kaudu käske käivitada.
* **Ärakasutamine:** CISA dokumenteeris aktiivse SolarWinds Orion kompromissi ja selle kasutamist. ([CISA][15])
* **Õppetund:** tarneahela risk ei pruugi olla Windowsi kernelis; haavatav võib olla Windowsi infrastruktuuris töötav haldustarkvara.

**CVE-2021-35211 – SolarWinds Serv-U**

* **Komponent:** SolarWinds Serv-U.
* **Tehniline probleem:** mälukaitsega seotud haavatavus.
* **Mõju:** kaugelt koodi käivitamise võimalus.
* **Ärakasutamine:** CISA KEV kirjeldab seda SolarWinds Serv-U RCE-na ja märgib selle kasutamist. ([CISA][16])
* **Parandus:** tootja turvauuendus.

### Linux

**CVE-2024-3094 – XZ Utils**

* **Komponent:** XZ Utils/liblzma.
* **Tehniline probleem:** pahatahtlikult muudetud upstream-koodi kaudu loodud tarneahela kompromiss.
* **Eeltingimus:** mõjutatud XZ versiooni jõudmine Linuxi distributsiooni ja selle kasutamine.
* **Mõju:** kompromiteeritud kood võis mõjutada SSH-serveri autentimisahelat.
* **Oluline:** tegemist ei olnud lihtsalt tavapärase programmeerimisveaga, vaid tarneahela kompromissiga.
* **Parandus:** mõjutatud paketi eemaldamine/asendamine distributsiooni tootja juhiste järgi.

**CVE-2021-44228 – Log4Shell**

* **Komponent:** Apache Log4j2 `log4j-core`.
* **Seos Linuxiga:** Log4j võib töötada Linuxi serverirakendustes.
* **Tehniline probleem:** JNDI-funktsioonide kaudu sai kontrollitud sisend teatud tingimustel põhjustada kaugelt koodi laadimise.
* **Mõju:** RCE.
* **Ärakasutamine:** haavatavus sai väga laialdase reaalse kasutuse ja oli CISA KEV-is.
* **Parandus:** Log4j uuendamine vähemalt parandatud versioonile. ([NVD][17])

Siin on oluline eristus: **CVE-2021-44228 ei ole Linuxi kerneli haavatavus**, vaid Linuxis sageli töötava rakenduse sõltuvuse haavatavus. See ongi tarneahela õpetamisel oluline mõte.

### macOS

**CVE-2023-38545 – curl**

* **Komponent:** curl/libcurl.
* **Seos macOSiga:** Apple on dokumenteerinud selle haavatavuse macOS-i turvauuenduses.
* **Tehniline probleem:** SOCKS5 hostname töötlemisega seotud heap buffer overflow.
* **Mõju:** mälukorruptsioon.
* **Parandus:** Apple uuendas vastavat komponenti. ([Apple Support][3])

**CVE-2023-38546 – curl**

* **Komponent:** curl/libcurl.
* **Tehniline probleem:** curl SOCKS5 töötlemisega seotud turvaprobleem.
* **Seos:** Apple dokumenteeris CVE macOS Monterey turvauuenduses.
* **Mõju:** sõltub sellest, kuidas mõjutatud komponenti kasutav rakendus curl/libcurl funktsioone rakendas.
* **Parandus:** komponentide uuendamine. ([Apple Support][3])

### Android

Android kasutab väga suurt hulka AOSP, Linuxi kerneli, tootjate ja kolmandate osapoolte komponente. Seetõttu on tarneahela puhul oluline arvestada ka seadmetootja ja kiibitootja tasandiga.

**CVE-2024-43705 – PowerVR GPU**

* **Komponent:** Imagination Technologies PowerVR GPU.
* **Seos Androidiga:** Androidi turvabülletään käsitleb seda eraldi riistvarakomponendi turvaprobleemina.
* **Mõju:** sõltub mõjutatud GPU komponendist ja seadme tootja integreerimisest.
* **Parandus:** tootja/Androidi vastav turvauuendus. ([Android Open Source Project][9])

**CVE-2024-46973 – PowerVR GPU**

* **Komponent:** PowerVR GPU.
* **Tehniline olemus:** kolmanda osapoole riistvara-/draiverikomponent Androidi seadmes.
* **Mõju:** võib mõjutada Androidi seadme turvamudelit.
* **Parandus:** vastava tootja turvauuendus. ([Android Open Source Project][9])

### iOS

iOS-i puhul on tarneahela mõiste veidi erinev, sest Apple kontrollib suuremat osa süsteemitarkvara ja rakenduste levitamise ahelast. Samas kasutatakse süsteemis kolmandate osapoolte avatud lähtekoodiga komponente.

**CVE-2023-38545 – curl**

* **Komponent:** curl.
* **Tehniline probleem:** SOCKS5 töötlemisega seotud heap buffer overflow.
* **Mõte:** probleem võib jõuda platvormi ka avatud lähtekoodiga komponendi kaudu.
* **Parandus:** vastava süsteemikomponendi uuendamine.

**CVE-2023-38546 – curl**

* **Komponent:** curl.
* **Tehniline probleem:** curl SOCKS5 töötlemise probleem.
* **Mõju:** sõltub mõjutatud komponendi kasutusest.
* **Parandus:** Apple'i vastav süsteemiuuendus.

Tarneahela puhul tuleb seega alati küsida: **„Kust see tarkvarakomponent pärineb ja kes seda kontrollib?“**, mitte ainult „Kas minu rakendus on uuendatud?“.

## Kuidas riski vähendada?

Tarkvara tuleb hankida usaldusväärsetest allikatest. Organisatsioon peaks pidama nimekirja kasutatavatest tarkvarakomponentidest ja võimalusel kasutama SBOM-i (Software Bill of Materials). SBOM näitab, millistest komponentidest tarkvara koosneb. Sõltuvusi tuleb regulaarselt kontrollida teadaolevate CVE-de vastu. Tarkvara digitaalallkirju tuleb kontrollida, kuid allkirja olemasolu ei tähenda automaatselt, et tarkvara ise on turvaline. Uuenduste puhul tuleb hinnata ka tootja usaldusväärsust ja uuenduskanalit. Build-systemid ja pakettide allkirjastamise võtmed tuleb eraldi kaitsta. Organisatsioonis tuleks kriitilised kolmandate osapoolte komponendid kaardistada, et ühe tarnija kompromiteerimisel oleks teada, milliseid süsteeme see mõjutab.

---

# 20. Information Disclosure ehk teabe avalikustumine

Information disclosure tähendab olukorda, kus süsteem avalikustab andmeid kasutajale või protsessile, kellel ei tohiks neile andmetele ligipääsu olla. Avalikustunud info võib olla väga erineva tundlikkusega. Selleks võivad olla näiteks kasutajanimed, failisisu, paroolid, autentimismärgid, krüptograafilised võtmed, protsessimälu, kerneli mälu või süsteemi sisemine konfiguratsioon. Mõnikord tundub infoleke esmapilgul väikese probleemina. Tegelikult võib väike infoleke olla suurema ründeahela oluline osa. Näiteks võib ründaja kõigepealt saada teada protsessi mäluaadressi ning kasutada seda hiljem mõne mälukorruptsiooni haavatavuse ärakasutamiseks. Teisel juhul võib lekkinud autentimismärk võimaldada kasutajana sisselogimist. Infoleke võib tekkida tarkvaravea, vale ligipääsukontrolli, logimise, vigase veateate või mälukäsitluse tõttu. Mõned infolekke haavatavused puudutavad kasutajaandmeid, teised aga süsteemi sisemist tehnilist infot. Seetõttu tuleb information disclosure'i käsitleda nii konfidentsiaalsuse kui ka teiste turvaomaduste seisukohalt. CIA-mudelis (Confidentiality, Integrity, Availability) puudutab see eelkõige konfidentsiaalsust.

## Kuidas information disclosure tehniliselt tekib?

Üks tüüpiline näide on olukord, kus programm loeb mälust rohkem andmeid kui tegelikult vaja.

```text
Rakendus
   |
   | loe andmeid
   v
+----------------------+
| Mälu                 |
| [lubatud andmed]     |
| [teise objekti info] |
+----------------------+
          |
          v
   Liiga palju infot
          |
          v
      Ründaja
```

Teine võimalus on vigane juurdepääsukontroll.

```text
Kasutaja
   |
   | päring
   v
Rakendus
   |
   | puudulik autoriseerimine
   v
Teise kasutaja andmed
```

Kolmas võimalus on veateade. Kui programm annab kasutajale täieliku stack trace'i, failitee või andmebaasiühenduse detailid, võib see anda ründajale süsteemi kohta väärtuslikku infot.

## Mida ründaja pärast infolekke kasutamist teha võib?

Lekkinud andmeid võib kasutada järgmise rünnaku ettevalmistamiseks. Näiteks võib kasutaja nime, süsteemi versiooni ja sisemise IP-aadressi kombinatsioon anda ründajale palju parema ülevaate ohvri keskkonnast.

Kui lekib autentimismärk, võib ründaja proovida kasutada seda kasutaja asemel. Kui lekib krüptograafiline võti, võib mõju olla veel suurem.

Kerneli või protsessi mäluaadresside lekkimine võib aidata mööda minna mõnest mälukaitse mehhanismist. Seetõttu võib infoleke olla ründeahelas **abivahend**, mis muudab mõne teise haavatavuse kasutamise lihtsamaks.

### Windows

**CVE-2020-0601 – Windows CryptoAPI**

* **Komponent:** Windows CryptoAPI.
* **Tehniline probleem:** ECC-sertifikaatide valideerimise viga.
* **Mõju:** ründaja võis luua sertifikaadi, mis näis usaldusväärsem kui tegelikult oli.
* **Oluline:** see ei ole klassikaline „fail lekib välja“ infoleke, vaid usaldusmudelit mõjutav CryptoAPI probleem.
* **Parandus:** Windowsi turvauuendus. Microsoft kirjeldas riski kui sertifikaatide spoofing'u probleemi.

**CVE-2024-38130 – Windows Kernel**

* **Komponent:** Windows Kernel.
* **Tehniline probleem:** kerneli andmete avalikustumine.
* **Eeltingimus:** lokaalne ligipääs mõjutatud süsteemile.
* **Mõju:** kaitstud süsteemiinfo lekkimise võimalus.
* **Parandus:** Windowsi turvauuendus.

### Linux

**CVE-2022-44268 – ImageMagick**

* **Komponent:** ImageMagick.
* **Tehniline probleem:** spetsiaalselt koostatud PNG-fail võis põhjustada protsessimälu andmete lekkimist.
* **Mõju:** tundlik informatsioon võis jõuda faili või kasutajani.
* **Parandus:** ImageMagick uuendamine.

**CVE-2014-0160 – OpenSSL Heartbleed**

* **Komponent:** OpenSSL.
* **Tehniline probleem:** TLS heartbeat'i töötlemise viga põhjustas piiridest väljapoole lugemist.
* **Mõju:** serveri mälust võis lekkida paroole, sessioonitunnuseid või muud tundlikku infot.
* **Eeltingimus:** haavatav OpenSSL versioon.
* **Ärakasutamine:** probleem oli laialdaselt reaalselt kasutatav ja tekitas ulatusliku turvauuenduste laine.
* **Parandus:** OpenSSL-i uuendamine ja kompromiteeritud saladuste vahetamine.

### macOS

**CVE-2024-27863 – macOS Kernel**

* **Komponent:** kernel.
* **Tehniline probleem:** tundliku kerneliinfo avalikustumine logide kaudu.
* **Mõju:** lokaalne ründaja võis saada infot kerneli mälupaigutuse kohta.
* **Parandus:** Apple vähendas logidesse jõudva tundliku info hulka. ([Apple Support][18])

**CVE-2024-54500 – ImageIO**

* **Komponent:** macOS ImageIO.
* **Tehniline probleem:** pahatahtliku pildi töötlemisel võis tekkida protsessimälu avalikustumine.
* **Mõju:** tundlik mälus olev info võis lekkida.
* **Parandus:** Apple rakendas täiendavad kontrollid. ([Apple Support][13])

### Android

**CVE-2025-32317**

* **Komponent:** Android Framework.
* **Tüüp:** Information Disclosure.
* **Mõju:** mõjutatud Androidi versioonis võis haavatavus põhjustada tundliku info avalikustumise.
* **Parandus:** Android 16 vastav turvauuendus. ([Android Open Source Project][19])

**CVE-2025-26461**

* **Komponent:** Android Framework.
* **Tüüp:** Information Disclosure.
* **Mõju:** tundliku süsteemiinfo avalikustumise võimalus.
* **Parandus:** Androidi turvauuendus. ([Android Open Source Project][19])

Androidi turvabülletäänid kasutavad infolekete tähistamiseks lühendit **ID (Information Disclosure)** ning avaldavad mõjutatud komponendi, CVE ja parandatud AOSP versioonid. ([Android Open Source Project][19])

### iOS

**CVE-2024-27805 – Core Data**

* **Komponent:** iOS Core Data.
* **Tehniline probleem:** keskkonnamuutujate valideerimise probleem.
* **Mõju:** rakendus võis saada ligipääsu tundlikele kasutajaandmetele.
* **Parandus:** Apple parandas keskkonnamuutujate valideerimist. ([Apple Support][20])

**CVE-2020-27950 – Kernel**

* **Komponent:** iOS kernel.
* **Tehniline probleem:** kerneli mälu avalikustumine.
* **Mõju:** pahatahtlik rakendus võis lugeda kerneli mälu.
* **Ärakasutamine:** Apple teatas selle haavatavuse puhul, et oli teadlik aktiivsest ärakasutamisest.
* **Parandus:** vastav iOS-i turvauuendus.

## Kuidas riski vähendada?

Kõigepealt tuleb piirata seda, millistel kasutajatel ja protsessidel on ligipääs tundlikele andmetele. Rakendused peaksid järgima vähimate õiguste põhimõtet (least privilege). Logidesse ei tohiks kirjutada paroole, autentimismärke, krüptograafilisi võtmeid ega muud tundlikku infot. Veateated peaksid olema kasutajale piisavad, kuid mitte avaldama sisemist süsteemiinfot. Operatsioonisüsteemi ja rakendusi tuleb regulaarselt uuendada. Mobiilseadmetes vähendavad sandbox ja õiguste süsteemid ühe rakenduse ligipääsu teiste rakenduste andmetele. Serverites tuleks kontrollida ka varukoopiaid, logisid ja ajutisi faile, sest tundlik info võib lekkida ka nendest. Infolekke avastamisel tuleb hinnata, millised andmed tegelikult avalikustusid ja kas lekkinud tunnuseid, paroole või võtmeid tuleb kehtetuks muuta.

Need peatükid 16–20 lõpetavad 20-osalise osa. Eriti **19. peatükis** on oluline mõista, et tarneahela CVE ei ole tingimata operatsioonisüsteemi enda viga — haavatav komponent võib olla OS-is või selle peal töötavas tarkvaras, kuid risk jõuab ikkagi operatsioonisüsteemi keskkonda. See eristus tasub õpikus alles jätta.

[1]: https://msrc.microsoft.com/update-guide/en-us/?utm_source=chatgpt.com "Security Update Guide - Microsoft"
[2]: https://ubuntu.com/security/notices?utm_source=chatgpt.com "Security notices | Ubuntu"
[3]: https://support.apple.com/en-ca/120305?utm_source=chatgpt.com "About the security content of macOS Monterey 12.7.3 - Apple Support (CA)"
[4]: https://source.android.com/docs/security/bulletin/2024-11-01?utm_source=chatgpt.com "Android Security Bulletin November 2024  |  Android Open Source Project"
[5]: https://support.apple.com/en-ca/120304?utm_source=chatgpt.com "About the security content of iOS 17.3 and iPadOS 17.3 - Apple Support (CA)"
[6]: https://ubuntu.com/security/notices?details=kernel&offset=920&order=newest&utm_source=chatgpt.com "Security notices | Ubuntu"
[7]: https://support.apple.com/en-ke/120895?utm_source=chatgpt.com "About the security content of macOS Sonoma 14.4 - Apple Support (KE)"
[8]: https://support.apple.com/en-tj/120886?utm_source=chatgpt.com "About the security content of macOS Ventura 13.6.5 - Apple Support (TJ)"
[9]: https://source.android.com/docs/security/bulletin/2025-02-01?utm_source=chatgpt.com "Android Security Bulletin-February 2025  |  Android Open Source Project"
[10]: https://support.apple.com/en-ae/120905?utm_source=chatgpt.com "About the security content of iOS 17.5 and iPadOS 17.5 - Apple Support (AE)"
[11]: https://source.android.com/docs/security/bulletin/2024-06-01?utm_source=chatgpt.com "Android Security Bulletin—June 2024  |  Android Open Source Project"
[12]: https://nvd.nist.gov/vuln/detail/cve-2023-4863?utm_source=chatgpt.com "NVD-CVE-2023-4863"
[13]: https://support.apple.com/en-om/121839?utm_source=chatgpt.com "About the security content of macOS Sequoia 15.2 - Apple Support (OM)"
[14]: https://source.android.com/docs/security/bulletin/2024-03-01?utm_source=chatgpt.com "Android Security Bulletin—March 2024  |  Android Open Source Project"
[15]: https://www.cisa.gov/news-events/alerts/2020/12/13/active-exploitation-solarwinds-software?utm_source=chatgpt.com "Active Exploitation of SolarWinds Software | CISA"
[16]: https://www.cisa.gov/known-exploited-vulnerabilities-catalog?f%5B0%5D=vendor_project%3A800&f%5B10%5D=vendor_project%3A862&f%5B11%5D=vendor_project%3A875&f%5B12%5D=vendor_project%3A886&f%5B13%5D=vendor_project%3A888&f%5B14%5D=vendor_project%3A893&f%5B15%5D=vendor_project%3A902&f%5B16%5D=vendor_project%3A905&f%5B17%5D=vendor_project%3A911&f%5B18%5D=vendor_project%3A917&f%5B19%5D=vendor_project%3A932&f%5B1%5D=vendor_project%3A801&f%5B20%5D=vendor_project%3A938&f%5B21%5D=vendor_project%3A949&f%5B22%5D=vendor_project%3A1147&f%5B23%5D=vendor_project%3A1267&f%5B2%5D=vendor_project%3A817&f%5B3%5D=vendor_project%3A819&f%5B4%5D=vendor_project%3A823&f%5B5%5D=vendor_project%3A827&f%5B6%5D=vendor_project%3A837&f%5B7%5D=vendor_project%3A840&f%5B8%5D=vendor_project%3A854&f%5B9%5D=vendor_project%3A859&page=9&utm_source=chatgpt.com "Known Exploited Vulnerabilities Catalog | CISA"
[17]: https://nvd.nist.gov/vuln/detail/cve-2021-44228?utm_source=chatgpt.com "NVD-CVE-2021-44228"
[18]: https://support.apple.com/en-mide/120911?utm_source=chatgpt.com "About the security content of macOS Sonoma 14.6 - Apple Support"
[19]: https://source.android.com/docs/security/bulletin/android-16?utm_source=chatgpt.com "Android 16 Security Release Notes  |  Android Open Source Project"
[20]: https://support.apple.com/en-euro/120898?utm_source=chatgpt.com "About the security content of iOS 16.7.8 and iPadOS 16.7.8 - Apple Support"
