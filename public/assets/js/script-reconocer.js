document.addEventListener('DOMContentLoaded', async () => {
  const video = document.getElementById('video');
  const status = document.getElementById('status');
  const contenedor = document.getElementById('contenedor');

  const modal = document.getElementById('modalConfirmacion');
  const empNombre = document.getElementById('empNombre');
  const empEstatus = document.getElementById('empEstatus');
  const mensajeConfirmacion = document.getElementById('mensajeConfirmacion');
  const accionesConfirmacion = document.getElementById('accionesConfirmacion');

  let empleadoActual = null;
  let bloqueado = false;

  const MODEL_URL = '/assets/models';
  const CSRF_TOKEN = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');

  /* ================= MODELOS ================= */
  await Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
    faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
  ]);

  status.textContent = 'Modelos cargados, iniciando cámara...';

  /* ================= CÁMARA ================= */
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
  } catch (err) {
    status.textContent = 'Error al acceder a la cámara';
    return;
  }

  video.addEventListener('play', () => {
    const canvas = faceapi.createCanvasFromMedia(video);
    contenedor.append(canvas);

    const displaySize = {
      width: video.videoWidth,
      height: video.videoHeight
    };

    canvas.width = displaySize.width;
    canvas.height = displaySize.height;

    faceapi.matchDimensions(canvas, displaySize);
    const ctx = canvas.getContext('2d');

    setInterval(async () => {
      if (bloqueado) return;

      const detection = await faceapi
        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
          inputSize: 416,
          scoreThreshold: 0.4
        }))
        .withFaceLandmarks()
        .withFaceDescriptor();

      ctx.clearRect(0, 0, canvas.width, canvas.height);
      if (!detection) return;

      const resized = faceapi.resizeResults(detection, displaySize);
      const box = resized.detection.box;

      ctx.strokeStyle = '#00ff00';
      ctx.lineWidth = 3;
      ctx.strokeRect(box.x, box.y, box.width, box.height);
      ctx.fillText('Rostro detectado', box.x, box.y - 10);

      bloqueado = true;
      status.textContent = 'Verificando identidad...';

      try {
        const res = await fetch('/checador/identificar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
          },
          body: JSON.stringify({
            descriptor: Array.from(resized.descriptor)
          })
        });

        const json = await res.json();

        if (res.ok && json.ok) {
          mostrarModalIdentidad(json.empleado);
        } else {
          status.textContent = 'Rostro no reconocido';
          desbloquearConDelay();
        }

      } catch {
        status.textContent = 'Error de comunicación';
        desbloquearConDelay();
      }
    }, 300);
  });

  /* ================= MODAL ================= */

  function mostrarModalIdentidad(empleado) {
    empleadoActual = empleado;

    empNombre.textContent = empleado.nombre_completo;
    empEstatus.textContent = `Estatus: ${empleado.estatus}`;
    mensajeConfirmacion.textContent = '¿Confirmas que eres tú?';

    accionesConfirmacion.innerHTML = '';
    crearBoton('Sí, soy yo', registrarAsistencia, 'btn-ok');
    crearBoton('No soy yo', cancelar, 'btn-cancel');

    modal.classList.add('show');
  }

  function mostrarModalConfirmacion(mensaje, opciones) {
    mensajeConfirmacion.textContent = mensaje;
    accionesConfirmacion.innerHTML = '';

    // Botones de acción enviados por el backend
    opciones.forEach(opcion => {
      crearBoton(
        opcion.replace('_', ' '),
        () => confirmarAccion(opcion),
        'btn-ok'
      );
    });

    // Botón cancelar
    crearBoton(
      'Cancelar',
      cancelarConfirmacion,
      'btn-cancel'
    );

    modal.classList.add('show');
  }

  function crearBoton(texto, accion, clase) {
    const btn = document.createElement('button');
    btn.textContent = texto;
    btn.className = clase;
    btn.onclick = accion;
    accionesConfirmacion.appendChild(btn);
  }

  function cerrarModal() {
    modal.classList.remove('show');
  }

  function cancelarConfirmacion() {
    status.textContent = 'Operación cancelada';
    cerrarModal();
    desbloquearConDelay();
  }

  /* ================= REGISTRO ================= */

  async function registrarAsistencia() {
    status.textContent = 'Registrando asistencia...';

    try {
      const res = await fetch('/checador/registrar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
          empleado_id: empleadoActual.id_empleado
        })
      });

      const json = await res.json();

      if (json.confirmacion) {
        mostrarModalConfirmacion(json.data.mensaje, json.data.opciones);
        return;
      }

      status.textContent = json.message;
      cerrarModal();
      desbloquearConDelay();

    } catch {
      status.textContent = 'Error al registrar';
      cerrarModal();
      desbloquearConDelay();
    }
  }

  async function confirmarAccion(tipo) {
    status.textContent = 'Registrando...';

    try {
      const res = await fetch('/checador/registrar', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
          empleado_id: empleadoActual.id_empleado,
          accion_confirmada: tipo
        })
      });

      const json = await res.json();
      status.textContent = json.message;

    } catch {
      status.textContent = 'Error al confirmar';
    }

    cerrarModal();
    desbloquearConDelay();
  }

  function cancelar() {
    status.textContent = 'Identificación cancelada';
    cerrarModal();
    desbloquearConDelay();
  }

  function desbloquearConDelay() {
    setTimeout(() => {
      bloqueado = false;
      status.textContent = 'Esperando rostro...';
    }, 1500);
  }
});