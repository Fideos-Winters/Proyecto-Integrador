@extends('layouts.app')

@section('titulo', content: 'Calendario')

@section('contenido')

<style>
    /*
     * ISO 9241-307 / WCAG 2.1 AA — Ratios verificados:
     *  #fff sobre #010e6b → 15.3:1 ✅
     *  #fff sobre #703b94 →  5.1:1 ✅
     *  #be74be / #d481d2  →  solo decorativos, nunca fondo de texto
     */
    .fc { font-family: 'DM Sans', sans-serif; font-size: .85rem; color: #1f2937; }
    .fc .fc-toolbar-title { font-family: 'Sora', sans-serif; font-size: 1.1rem; font-weight: 700; color: #010e6b; }
    .fc .fc-button {
        background: #010e6b !important; border: none !important;
        border-radius: 8px !important; font-size: .78rem !important;
        font-weight: 600 !important; padding: .35rem .75rem !important;
        color: #ffffff !important; transition: background .15s !important;
    }
    .fc .fc-button:hover, .fc .fc-button-active { background: #703b94 !important; }
    .fc .fc-button:focus { box-shadow: 0 0 0 3px rgba(190,116,190,.4) !important; outline: none !important; }
    .fc .fc-col-header-cell-cushion {
        color: #010e6b; font-weight: 600;
        font-size: .75rem; text-transform: uppercase; letter-spacing: .05em;
    }
    .fc .fc-daygrid-day-number { color: #374151; font-weight: 500; padding: .3rem .5rem; }
    .fc .fc-day-today { background: #f3f0f8 !important; }
    .fc .fc-day-today .fc-daygrid-day-number {
        background: #010e6b; color: #fff; border-radius: 50%;
        width: 26px; height: 26px; display: flex;
        align-items: center; justify-content: center; font-weight: 700;
    }
    .fc-event {
        border: none !important; border-radius: 6px !important;
        padding: 2px 6px !important; font-size: .75rem !important;
        font-weight: 600 !important; cursor: pointer;
    }
    .fc-event:hover { filter: brightness(1.12); }
    .fc .fc-scrollgrid { border-color: #ede9f6 !important; }
    .fc td, .fc th { border-color: #ede9f6 !important; }
    .cita-card {
        border-radius: 10px; padding: .75rem 1rem;
        display: flex; align-items: flex-start; gap: .75rem;
        background: #f8f5ff; transition: transform .15s;
    }
    .cita-card:hover { transform: translateX(3px); }
    .cita-dot    { width: 10px; height: 10px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
    .cita-fecha  { font-size: .7rem;  font-weight: 600; color: #6b7280; }
    .cita-titulo { font-size: .83rem; font-weight: 600; color: #111827; }
    .cita-hora   { font-size: .72rem; color: #9ca3af; }
</style>

<section class="py-8 px-4 mx-auto max-w-screen-xl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold" style="font-family:'Sora',sans-serif; color:#010e6b;">Calendario de Citas</h1>
        <p class="text-sm text-gray-500 mt-1">Gestiona y visualiza todas las citas programadas</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm p-5" style="border:1px solid #ede9f6;">
                <div id="calendar"></div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm p-5 h-full" style="border:1px solid #ede9f6;">

                <h2 class="text-base font-bold mb-4" style="font-family:'Sora',sans-serif; color:#010e6b;">
                    Próximas citas
                </h2>

                <ul class="space-y-3" id="lista-citas"></ul>

                

            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Jalamos los eventos reales desde Laravel
    var eventos = @json($eventos);

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView:   'dayGridMonth',
        locale:        'es',
        headerToolbar: { 
            left: 'prev,next today', 
            center: 'title', 
            right: 'dayGridMonth,timeGridWeek' 
        },
        buttonText:    { today: 'Hoy', month: 'Mes', week: 'Semana' },
        events:        eventos, // <--- Aquí ya se cargan solas
        height:        'auto',
        eventClick: function(info) {
            alert('Paciente: ' + info.event.extendedProps.paciente + '\nHora: ' + info.event.extendedProps.hora_formateada);
        }
    });

    calendar.render();

    // 2. Llenar la lista de la derecha (Próximas citas)
    var lista = document.getElementById('lista-citas');
    var opciones = { weekday: 'short', day: 'numeric', month: 'short' };
    
    // Opcional: Filtrar solo las próximas para la lista lateral
    var hoy = new Date();
    var proximas = eventos.filter(ev => new Date(ev.start) >= hoy)
                          .sort((a, b) => new Date(a.start) - new Date(b.start))
                          .slice(0, 5); // Solo mostrar las 5 más cercanas

    lista.innerHTML = ''; // Limpiamos la lista estática

    proximas.forEach(function(ev) {
        var inicio = new Date(ev.start);
        var li = document.createElement('li');
        li.innerHTML = `
            <div class="cita-card">
                <span class="cita-dot" style="background:${ev.color};"></span>
                <div>
                    <p class="cita-fecha">${inicio.toLocaleDateString('es-MX', opciones)}</p>
                    <p class="cita-titulo">${ev.paciente}</p>
                    <p class="cita-hora">${ev.hora_formateada}</p>
                </div>
            </div>`;
        lista.appendChild(li);
    });

    if (proximas.length === 0) {
        lista.innerHTML = '<p class="text-xs text-center text-gray-400 py-4">No hay citas próximas</p>';
    }
});

// --- El Secreto de la Ceniza (Konami Code) ---
const konamiCode = [
    'ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 
    'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 
    'b', 'a'
];
let count = 0;

document.addEventListener('keydown', function(e) {
    // Verificamos si la tecla presionada es la correcta en la secuencia
    if (e.key === konamiCode[count]) {
        count++;
        // Si se completa la secuencia...
        if (count === konamiCode.length) {
            activarEasterEgg();
            count = 0; // Reiniciamos el contador
        }
    } else {
        count = 0; // Si falla, vuelve a empezar
    }
});

function activarEasterEgg() {
    // Aquí ocurre la magia. Por ejemplo, cambiar los colores de la orden.
    alert("Se ve que si le sabes Luisito.!");
    
    // Un toque visual: invertimos los colores del calendario
    document.body.style.filter = "invert(1) hue-rotate(180deg)";
    document.body.style.transition = "filter 2s ease";
}
</script>

@endsection