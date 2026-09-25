**IDOR** ehk *Insecure Direct Object Reference* (ebaturvaline otsene viide objektile) on üks levinumaid ja ohtlikumaid turvaauke veebirakendustes. See on täpselt seesama "horisontaalse õiguste suurendamise" olukord, mida eelmises vastuses põgusalt mainisime.

**Kuidas seda kõige lihtsamalt ette kujutada?**
Kujuta ette teatri riidehoidu. Sa annad oma jope ja saad vastu numbri 15. Kui sa hiljem järele lähed, ütled riidehoidjale: "Palun andke mulle jope number 16." Kui riidehoidja (ehk süsteem) annab sulle küsimusi esitamata võõra jope lihtsalt sellepärast, et sa seda numbrit küsisid, ongi tegemist IDOR-iga. Süsteem usaldas sinu antud sisendit (numbrit 16), kuid ei kontrollinud, kas sul on tegelikult **õigus** seda asja saada.

**Kuidas IDOR IT-süsteemis välja näeb?**
Oletame, et logid sisse e-poodi ja soovid vaadata oma viimast tellimust või arvet. Veebibrauseri aadressireal on näiteks selline link:
`[https://www.epood.ee/minu-konto/arved?arve_id=1055](https://www.epood.ee/minu-konto/arved?arve_id=1055)`

Siin on arv "1055" **otsene viide objektile** (sinu arvele andmebaasis). IDOR haavatavus tekib siis, kui ründaja (või isegi uudishimulik tavakasutaja) muudab selle numbri aadressireal näiteks "1056-ks" ja vajutab Enter:
`[https://www.epood.ee/minu-konto/arved?arve_id=1056](https://www.epood.ee/minu-konto/arved?arve_id=1056)`

Kui server taustal ei kontrolli, kas praegu sisselogitud kasutajal on lubatud näha arvet numbriga 1056, vaid kuvabki selle dokumendi lihtsalt ekraanile, on ründaja saanud ligipääsu võõra inimese andmetele.

**Mida ründaja IDOR-iga teha saab?**

* **Andmete varastamine (lugemine):** Ründaja võib kirjutada lihtsa programmi (skripti), mis käib mõne minutiga läbi kõik numbrid (1 kuni 100 000) ja laadib alla tuhandete teiste kasutajate isiklikud arved, terviseandmed, lepingud või privaatsed sõnumid.
* **Andmete muutmine (kirjutamine):** Kui profiili muutmise päring saadetakse kujul `epood.ee/muuda_profiili?kasutaja_id=50`, võib ründaja proovida muuta kellegi teise andmeid, sisestades võõra kasutaja ID numbri.
* **Andmete kustutamine:** Kui faili kustutamise link on `kustuta_pilt?id=12`, võib ründaja hakata järjest numbreid vahetades teiste kasutajate üleslaaditud faile andmebaasist hävitama.

**Miks see juhtub?**
IDOR tekib siis, kui programmeerija kontrollib küll seda, kas kasutaja on süsteemi **sisse loginud** (tal on kehtiv token/seanss), kuid unustab kontrollida, kas tal on õigus seda konkreetset **objekti näha või muuta** (autoriseerimine).

Selle vältimiseks ei piisa ainult järjestikuste numbrite (1, 2, 3) asendamisest pikkade ja juhuslike koodidega (nn UUID, näiteks `arve_id=f47ac10b-58cc-4372-a567-0e02b2c3d479`). Kuigi see teeb ID-de pimesi äraarvamise väga raskemaks (seda nimetatakse *Security by Obscurity* ehk turvalisus läbi hägususe), on ainus tõeline lahendus see, et süsteem esitab iga kord faili või andmeid pärides andmebaasile küsimuse: *"Kas see dokument numbriga X kuulub praegu sisse loginud kasutajale Y?"*.
