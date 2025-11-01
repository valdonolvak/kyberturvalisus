Ubuntu Server kasutab võrguseadistuste haldamiseks tavaliselt **Netplan'i**, mis kasutab YAML-faile. Proxmoxi **VMBR number** asendab siin võrguliidese nime (nt `eth0`, `ens18` vms). Eeldame, et teie võrguliidese nimi on **`enpXs0`** (kus **`X`** on teie **VMBR number**), kuna Proxmoxis on tihti kasutusel ennustatavad võrguliidese nimed. Kontrollige seda siiski esmalt käsuga `ip a`.

Järgnev juhend kirjeldab **staatilise IP-aadressi** ja **nimeserveri** seadistamist Netplan'iga.

-----

## 💻 Ubuntu Serveri võrguseadistus (Netplan)

### 1\. 🔍 Leidke Netplan'i konfiguratsioonifail

Netplan'i konfiguratsioonifailid asuvad kataloogis `/etc/netplan/`. Tavaliselt on seal üks fail, näiteks `00-installer-config.yaml` või `50-cloud-init.yaml`.

  * **Loetlege failid:**
    ```bash
    ls /etc/netplan/
    ```
  * **Tehke varukoopia** enne muutmist (asendage `<failinimi>` oma tegeliku failinimega):
    ```bash
    sudo cp /etc/netplan/<failinimi>.yaml /etc/netplan/<failinimi>.yaml.bak
    ```

### 2\. ✏️ Muutke Netplan'i konfiguratsioonifaili

Kasutage tekstiredaktorit (nt `nano`) faili muutmiseks.

  * **Avage fail:**
    ```bash
    sudo nano /etc/netplan/<failinimi>.yaml
    ```
  * **Asendage või muutke sisu** järgmiseks. **Asendage** kindlasti:
      * **`<X>`** Proxmoxi **VMBR numbri** ja vastava IP-aadressi numbriga (nt kui VMBR on 0, võiks IP olla `10.0.0.100`).
      * **`<Proxmoxi_IP_värav>`** oma Proxmoxi hosti IP-aadressiga (tavaliselt see, mida kasutate väravana). Kui teie võrk on 10.0.X.0/24, on see tõenäoliselt **`10.0.X.1`**.
      * **`<enpXs0>`** oma tegeliku võrguliidese nimega (nt `enp0s1`, `ens18` vms).

<!-- end list -->

```yaml
network:
  version: 2
  renderer: networkd # Võib olla ka NetworkManager, sõltub paigaldusest
  ethernets:
    <enpXs0>: # ASENDA see oma võrguliidese nimega!
      dhcp4: no
      addresses: [10.0.X.100/24] # ASENDA X oma VMBR numbriga ja IP lõpuosa sobivaga!
      routes:
        - to: default
          via: 10.0.X.1 # ASENDA see oma värava IP-ga! (nt 10.0.0.1)
      nameservers:
        addresses: [1.1.1.1, 8.8.8.8] # Nimeserverid: 1.1.1.1 (Cloudflare) ja tagavaraks 8.8.8.8 (Google)
```

> **Märkus:** YAML on **taandetest (indents)** sõltuv\! Kasutage ainult **tühikuid**, mitte tabulaatoreid, ja järgige ülalt näidatud struktuuri.

### 3\. ✅ Rakendage ja kontrollige seadistust

Pärast faili salvestamist (nano puhul Ctrl+O, Enter, Ctrl+X) peate uued seaded rakendama:

  * **Kontrollige konfiguratsiooni (soovitatav):**
    ```bash
    sudo netplan try
    ```
    See proovib seadistust ja taastab vanad seaded automaatselt, kui 120 sekundi jooksul ühendus katkeb.
  * **Rakendage konfiguratsioon:**
    ```bash
    sudo netplan apply
    ```

### 4\. 🌐 Kontrollige ühenduvust

  * **Kontrollige uut IP-aadressi:**
    ```bash
    ip a
    ```
    Veenduge, et teie liidesel `<enpXs0>` oleks õige staatiline IP.
  * **Kontrollige värava ühenduvust:**
    ```bash
    ping -c 3 10.0.X.1 # ASENDA X uuesti õige numbriga
    ```
  * **Kontrollige nimeserveri (DNS) tööd:**
    ```bash
    ping -c 3 google.com
    ```
    Kui see õnnestub, töötab nii teie võrk kui ka nimeserver 1.1.1.1.

-----

Kui teil on ebaselge, mis on teie võrguliidese nimi või kui te ei leia õiget Netplan'i faili, saame seda edasi vaadata.

See video annab üldise ülevaate staatilise IP-aadressi seadistamisest Ubuntu Netplan'i abil, mis on sarnane teie vajadustega. [Using Netplan to set up a Static IP Configuration (Ubuntu Ungoliant)](https://www.youtube.com/watch?v=dUvgGXY9k6E)

Mida sooviksite järgmisena teha? Võin aidata näiteks tulemüüri (nt UFW) seadistamisega.
http://googleusercontent.com/youtube_content/0
