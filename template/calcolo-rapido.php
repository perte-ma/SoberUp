<p class="text-center text-muted mb-4">Stima il tasso alcolemico (BAC) con la formula di Widmark — nessun dato viene salvato</p>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        <form id="calcoloForm" class="card shadow-sm p-4 mb-4">
            <h2 class="h5">I tuoi dati</h2>
            <div class="mb-3">
                <label for="peso" class="form-label">Peso corporeo (kg)</label>
                <input type="number" class="form-control" id="peso" min="1" step="0.1" required>
            </div>
            <fieldset class="mb-0">
                <legend class="form-label">Sesso biologico</legend>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sesso" id="sessoM" value="M" required>
                    <label class="form-check-label" for="sessoM">M</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sesso" id="sessoF" value="F" required>
                    <label class="form-check-label" for="sessoF">F</label>
                </div>
            </fieldset>
        </form>

        <div class="card shadow-sm p-4 mb-4">
            <h2 class="h5">Drink bevuti</h2>
            <input type="text" class="form-control mb-2" id="ricercaDrink" placeholder="Cerca un drink...">
            <ul class="list-group mb-3" id="risultatiRicerca"></ul>

            <ul class="list-group" id="listaDrinkBevuti"></ul>
        </div>

        <button type="submit" form="calcoloForm" class="btn btn-primary w-100 mb-4">CALCOLA!</button>

        <div id="resultBox" class="alert d-none" role="alert"></div>

        <div class="disclaimer mx-auto">
            Il valore calcolato è una stima statistica basata sulla formula di Widmark. Non sostituisce un
            etilometro. In caso di dubbio, non guidare.
        </div>

    </div>
</div>

<script>
    const drinks = <?php echo json_encode($templateParams["drinks"]); ?>;
    
    let listaBevuti = [];

    function gramsOfAlcohol(volumeMl, abvPercent, densita = 0.789) {
        return volumeMl * (abvPercent / 100) * densita;
    }

    function bacWidmark(gramsAlcohol, weightKg, rFactor, hoursElapsed, eliminationRate = 0.15) {
        const bac = (gramsAlcohol / (weightKg * rFactor)) - (eliminationRate * hoursElapsed);
        return Math.max(0, bac);
    }

    function orarioComeDate(orarioStr, riferimento) {
        const [h, m] = orarioStr.split(':').map(Number);
        const data = new Date(riferimento);
        data.setHours(h, m, 0, 0);
        if (data > riferimento) {
            data.setDate(data.getDate() - 1);
        }
        return data;
    }

    const inputRicerca = document.getElementById('ricercaDrink');
    const risultatiRicerca = document.getElementById('risultatiRicerca');
    const listaDrinkBevuti = document.getElementById('listaDrinkBevuti');

    inputRicerca.addEventListener('input', function () {
        const termine = this.value.trim().toLowerCase();
        risultatiRicerca.innerHTML = '';
        if (termine == '') return;

        const trovati = drinks.filter(d => d.nomedrink.toLowerCase().includes(termine));
        trovati.forEach(d => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `<span>${d.nomedrink} <span class="text-muted small">(${d.gradazione}%)</span></span>
                <button type="button" class="btn btn-sm btn-outline-primary">+</button>`;
            li.querySelector('button').addEventListener('click', () => aggiungiDrink(d));
            risultatiRicerca.appendChild(li);
        });
    });

    function orarioAdesso() {
        return new Date().toTimeString().slice(0, 5);
    }

    function aggiungiDrink(drink) {
        listaBevuti.push({
            nomedrink: drink.nomedrink,
            gradazione: drink.gradazione,
            volume: drink.volume_standard,
            orario: orarioAdesso()
        });
        inputRicerca.value = '';
        risultatiRicerca.innerHTML = '';
        renderListaBevuti();
    }

    function renderListaBevuti() {
        listaDrinkBevuti.innerHTML = '';
        if (listaBevuti.length == 0) {
            listaDrinkBevuti.innerHTML = '<li class="list-group-item text-muted">Nessun drink aggiunto.</li>';
            return;
        }
        listaBevuti.forEach((d, i) => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <strong>${d.nomedrink}</strong>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" class="form-control form-control-sm inputVolume" style="width: 80px" value="${d.volume}" min="1" step="1">
                        <span class="small text-muted">ml</span>
                        <input type="time" class="form-control form-control-sm inputOrario" style="width: 100px" value="${d.orario}">
                        <button type="button" class="btn btn-sm btn-outline-danger">x</button>
                    </div>
                </div>`;

            li.querySelector('.inputVolume').addEventListener('input', function () {
                d.volume = this.value;
            });
            li.querySelector('.inputOrario').addEventListener('input', function () {
                d.orario = this.value;
            });
            li.querySelector('button').addEventListener('click', function () {
                listaBevuti.splice(i, 1);
                renderListaBevuti();
            });

            listaDrinkBevuti.appendChild(li);
        });
    }

    renderListaBevuti();

    document.getElementById('calcoloForm').addEventListener('submit', function (event) {
        event.preventDefault();

        if (listaBevuti.length == 0) {
            alert('Aggiungi almeno un drink prima di calcolare.');
            return;
        }

        const peso = document.getElementById('peso').value;
        const sesso = document.querySelector('input[name="sesso"]:checked').value;
        const rFactor = sesso == 'M' ? 0.68 : 0.55;

        let totalGrams = 0;
        let primoOrario = null;
        const adesso = new Date();

        listaBevuti.forEach(d => {
            totalGrams += gramsOfAlcohol(d.volume, d.gradazione);

            const orarioDrink = orarioComeDate(d.orario, adesso);
            if (primoOrario == null || orarioDrink < primoOrario) {
                primoOrario = orarioDrink;
            }
        });

        const oreTrascorse = Math.max(0, (adesso - primoOrario) / 3600000);

        if (oreTrascorse > 12) {
            alert('Il primo drink risulta più di 12 ore fa: controlla gli orari inseriti.');
            return;
        }

        const bac = bacWidmark(totalGrams, peso, rFactor, oreTrascorse);

        mostraRisultato(bac);
    });

    function mostraRisultato(bac) {
        const box = document.getElementById('resultBox');
        box.classList.remove('d-none', 'alert-success', 'alert-danger');

        let statoHtml;
        if (bac <= 0.5) {
            box.classList.add('alert-success');
            statoHtml = '<p class="mb-0">🟢 <strong>Puoi guidare</strong> — stima sotto il limite legale di 0.5 g/L.</p>';
        } else {
            box.classList.add('alert-danger');
            const minutiTotali = Math.round(((bac - 0.5) / 0.15) * 60);
            const ore = Math.floor(minutiTotali / 60);
            const minuti = minutiTotali % 60;

            let tempoTesto;
            if (ore == 0) {
                tempoTesto = `${minuti} minuti`;
            } else if (minuti == 0) {
                tempoTesto = `${ore} ore`;
            } else {
                tempoTesto = `${ore} ore e ${minuti} minuti`;
            }

            statoHtml = `<p class="mb-0">🔴 <strong>Non puoi guidare</strong> — torni sotto il limite tra circa ${tempoTesto}.</p>`;
        }

        box.innerHTML = `
            <h4 class="alert-heading mb-2">Tasso alcolemico stimato</h4>
            <p class="display-6 mb-2">${bac.toFixed(2)} g/L</p>
            ${statoHtml}
            <hr>
            <p class="mb-2">Registrandoti puoi: tenere traccia delle tue serate, condividere lo stato con gli amici, avere un calcolo più preciso col metodo di Watson, e il catalogo completo dei drink.</p>
            <a href="registrazione.php" class="btn btn-primary">Registrati!</a>`;

        box.scrollIntoView({ behavior: 'smooth' });
    }
</script>
