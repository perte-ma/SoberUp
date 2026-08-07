    <nav class="navbar navbar-dark bg-dark">
        <div class="container flex-wrap">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <span class="rounded-circle bg-light text-dark d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.7rem; font-weight: bold;">SU</span>
                SoberUp!
            </a>

            <!-- TODO: quando esistono Dashboard/Catalogo/Serata/Profilo-->

            <div class="form-check form-switch text-light mb-0">
                <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                <label class="form-check-label" for="darkModeSwitch">🌙</label>
            </div>
        </div>
    </nav>

    <script>
        function getPreferredTheme() {
            const salvato = localStorage.getItem("theme");
            if (salvato) return salvato;
            const ora = new Date().getHours();
            return (ora >= 20 || ora < 7) ? "dark" : "light";
        }

        function applyTheme(theme) {
            document.documentElement.setAttribute("data-theme", theme);
            document.getElementById("darkModeSwitch").checked = (theme === "dark");
        }

        applyTheme(getPreferredTheme());

        document.getElementById("darkModeSwitch").addEventListener("change", function() {
            const theme = this.checked ? "dark" : "light";
            localStorage.setItem("theme", theme);
            applyTheme(theme);
        });
    </script>
