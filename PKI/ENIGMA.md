
## 🗝️ ENIGMA Krüpteerimismasin: Lühiülevaade

**Enigma** oli elektromehaaniline rootoripõhine krüpteerimisseade, mida kasutati kaubanduslikuks, diplomaatiliseks ja eriti **Saksamaa relvajõudude** poolt laialdaselt **Teise maailmasõja ajal** sõnumite krüpteerimiseks. Seda peeti omal ajal äärmiselt turvaliseks, kuna see pakkus tohutult palju võimalikke krüpteerimiskonfiguratsioone.

### ⚙️ Kuidas see töötas?

Enigma töötas põhimõttel, et iga sisestatud täht asendati uue tähega, kuid see asendus muutus iga klahvivajutusega. Selle põhiosad olid:

* **Klaviatuur ja Lambitahvel (Valgustahvel):** Kasutaja sisestas sõnumi klaviatuuril ja krüpteeritud täht (või dekrüpteeritud täht) süttis lambitahvlil.
* **Rootorid:** Enigma tuum. Rootorid olid keeruka sisemise juhtmestikuga kettad. Need pöörlesid pärast iga tähe sisestamist, muutes elektriahelat ja seega ka krüpteerimist.
    * Tavaline mudel kasutas 3–4 rootorit, mida sai omavahel vahetada ja erinevalt järjestada.
* **Reflektor (Umkehrwalze):** See komponent saatis elektrivoolu tagasi läbi rootorite, kuid teist teed pidi. See oli Enigma ainulaadne omadus, kuid tagas ka selle, et ükski täht **ei krüpteeritud mitte kunagi iseendaks** (nt. A ei saanud kunagi krüpteeruda A-ks), mis osutus hiljem otsustavaks nõrkuseks.
* **Pistikplaat (Plugboard / Steckerbrett):** Lisafunktsioon sõjaväeversioonidel. See võimaldas vahetada tähepaare (nt. A ja L) enne rootoreid ja pärast neid, mis suurendas oluliselt krüpteerimise keerukust.

### 💥 Enigma lahtimurdmine

Kuigi Enigma oli keerukas, õnnestus Poola krüptoanalüütikutel ja hiljem Briti koodimurdjatel (nagu **Alan Turing**) Bletchley Parkis selle kood lahti murda. Nad kasutasid ära masina nõrkusi ja sakslaste operatiivseid vigu. Enigma salateadete lahtimurdmisest saadud luureandmed, tuntud kui **Ultra**, lühendasid sõja kestust märkimisväärselt.

---

## 📝 Näide (Lihtsustatud)

Tegelik Enigma krüpteerimine on rootorite pöörlemise ja pistikplaadi tõttu keeruline, kuid siin on lihtsustatud näide, mis illustreerib, kuidas **iga täht krüpteerib erinevalt:**

**Sõnum:** T E R E

1.  **T** sisestatakse → **Rootorid on asendis 1** → Krüpteerub: **V**
    * *Rootorid liiguvad ühe sammu võrra.*
2.  **E** sisestatakse → **Rootorid on asendis 2** → Krüpteerub: **K**
    * *Rootorid liiguvad ühe sammu võrra.*
3.  **R** sisestatakse → **Rootorid on asendis 3** → Krüpteerub: **L**
    * *Rootorid liiguvad ühe sammu võrra.*
4.  **E** sisestatakse → **Rootorid on asendis 4** → Krüpteerub: **S**

**Krüpteeritud Sõnum:** V K L S

**Oluline:** Kui saatja ja vastuvõtja seadsid oma (identse) masina (vastavalt igapäevasele koodivõtmele) samamoodi (samad rootorid, sama järjestus, samad algasendid ja samad pistikplaadi ühendused), pidi vastuvõtja dekrüpteerimiseks vaid krüpteeritud sõnumi (`VKLS`) masinasse sisestama ja see pöörati tagasi algseks sõnumiks (`TERE`).
