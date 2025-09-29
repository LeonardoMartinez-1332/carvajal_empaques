function inicializarIdioma() {
    console.log("Inicializando idioma...");

    const languageToggle = document.getElementById("languageToggle");
    if (!languageToggle) {
        console.error("No se encontró el botón de idioma.");
        return;
    }

    languageToggle.addEventListener("click", function () {
        console.log("Botón de idioma clickeado.");
        const currentLang = this.dataset.lang === "es" ? "en" : "es";
        console.log("Idioma actual:", currentLang);

        this.dataset.lang = currentLang;

        fetch("traducciones.json")
            .then(response => response.json())
            .then(data => {
                const traducciones = data[currentLang];
                console.log("Traducciones cargadas:", traducciones);

                for (const [key, value] of Object.entries(traducciones)) {
                    if (typeof value === "object") {
                        for (const [subkey, subvalue] of Object.entries(value)) {
                            const subelement = document.getElementById(subkey);
                            if (subelement) {
                                subelement.textContent = subvalue;
                                console.log(`Texto cambiado: ${subkey} -> ${subvalue}`);
                            } else {
                                console.warn(`No se encontró el elemento con ID: ${subkey}`);
                            }
                        }
                    } else {
                        const element = document.getElementById(key);
                        if (element) {
                            element.textContent = value;
                            console.log(`Texto cambiado: ${key} -> ${value}`);
                        } else {
                            console.warn(`No se encontró el elemento con ID: ${key}`);
                        }
                    }
                }
            })
            .catch(error => console.error("Error al cargar traducciones:", error));
    });
}
