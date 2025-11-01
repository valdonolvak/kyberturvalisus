Kui **VMBR number on alates 10-st** ja võrguliidese nimi on **`ens18`**, on meil olemas kõik vajalik Netplan'i faili loomiseks.

Juhendis kasutame muutuja **`X`** väärtust vastavalt teie VMBR numbrile (nt kui VMBR on **10**, siis **`X=10`**). Samuti eeldame, et soovite serverile staatilise IP-aadressi, näiteks **`.100`** selle võrgu sees, ja värav (gateway) on tavaliselt **`.1`** samas võrgus (nt `10.0.10.1`).

-----

## 🛠️ Ubuntu serveri võrguseadistuse juhend (Netplan)

Ubuntu Server kasutab võrgu konfigureerimiseks utiliiti **Netplan**, mille seaded on kirjas **YAML**-formaadis failides.

### 1\. 🔍 Konfiguratsioonifaili leidmine ja avamine

Netplan'i konfiguratsioonifailid asuvad kataloogis `/etc/netplan/`. Kasutage olemasolevat faili (nt `00-installer-config.yaml`) või looge uus.

1.  **Leidke olemasolev fail:**
    ```bash
    ls /etc/netplan/
    ```
2.  **Tehke varukoopia** (asendage `<failinimi>` oma tegeliku failinimega):
    ```bash
    sudo cp /etc/netplan/<failinimi>.yaml /etc/netplan/<failinimi>.yaml.bak
    ```
3.  **Avage fail redigeerimiseks** (kasutades tekstiredaktorit `nano`):
    ```bash
    sudo nano /etc/netplan/<failinimi>.yaml
    ```

### 2\. 📝 Netplan'i konfiguratsiooni sisu

Asendage faili sisu (või muutke vastavaid osi) järgmiseks.

#### Asendamist vajavad väärtused:

| Parameeter | Väärtus | Näide (kui VMBR=10) |
| :--- | :--- | :--- |
| **Liidese nimi** | `ens18` | `ens18` |
| **IP-aadress** | `10.0.X.100/24` | `10.0.10.100/24` |
| **Võrguvärav** | `10.0.X.1` | `10.0.10.1` |
| **Nimeserver** | `1.1.1.1` (ja soovi korral tagavara) | `1.1.1.1, 8.8.8.8` |

#### YAML-faili näidis:

```yaml
network:
  version: 2
  renderer: networkd # See võib olla ka NetworkManager, ärge muutke, kui te pole kindel
  ethernets:
    ens18: # Proxmoxi võrguliides
      dhcp4: no # Lülitame DHCP välja
      addresses: [10.0.X.100/24] # Staatiline IP koos alamvõrgumaskiga
      routes:
        - to: default
          via: 10.0.X.1 # Võrguvärav (Proxmoxi VMBR IP)
      nameservers:
        addresses: [1.1.1.1, 8.8.8.8] # Esimene nimeserver 1.1.1.1 ja tagavaraks Google DNS
```

> ⚠️ **Märkus YAML-i kohta:** Pöörake suurt tähelepanu **taandetele (indents)**. Iga tase peab olema taandatud **kahe tühikuga** (mitte tabulaatoriga).

### 3\. ✅ Seadistuse rakendamine

Pärast faili salvestamist (nano puhul Ctrl+O, Enter, Ctrl+X) rakendage uued seaded.

1.  **Testige konfiguratsiooni (soovitatav):**
    ```bash
    sudo netplan try
    ```
    Kui konfiguratsioonis on vigu või ühendus katkeb, taastatakse vanad seaded automaatselt 120 sekundi pärast.
2.  **Rakendage konfiguratsioon:**
    ```bash
    sudo netplan apply
    ```

### 4\. 🌐 Ühenduvuse kontrollimine

Kontrollige, kas uus staatiline IP-aadress ja nimeserver töötavad.

  * **Kontrollige IP-d:**
    ```bash
    ip a show ens18
    ```
  * **Kontrollige võrguväravani jõudmist:**
    ```bash
    ping -c 3 10.0.X.1
    ```
  * **Kontrollige DNS-i tööd (peaks kasutama 1.1.1.1):**
    ```bash
    ping -c 3 google.com
    ```

Kui kõik õnnestus, on võrk soovitud parameetritega seadistatud\!
