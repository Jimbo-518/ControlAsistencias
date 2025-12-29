document.addEventListener('DOMContentLoaded', async () => {
    const video = document.getElementById('video');
    const startBtn = document.getElementById('startBtn');
    const captureBtn = document.getElementById('captureBtn');
    const finishBtn = document.getElementById('finishBtn');
    const thumbs = document.getElementById('thumbs');
    const status = document.getElementById('status');
    const log = document.getElementById('log');
    const idEmpleado = document.getElementById('id_empleado').value;

    const descriptors = [];

    const tinyOptions = new faceapi.TinyFaceDetectorOptions({
        inputSize: 416,
        scoreThreshold: 0.5
    });

    function logMsg(msg) {
        log.textContent += msg + "\n";
        log.scrollTop = log.scrollHeight;
    }

    await Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('/assets/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/assets/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/assets/models'),
    ]);

    status.textContent = 'Modelos cargados. Inicia la cámara.';

    // Iniciar cámara
    startBtn.addEventListener('click', async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            captureBtn.disabled = false;
            logMsg('Cámara iniciada');
        } catch (e) {
            status.textContent = 'Error al iniciar cámara';
        }
    });

    function createThumb(box) {
        const c = document.createElement('canvas');
        c.width = box.width;
        c.height = box.height;
        const ctx = c.getContext('2d');
        ctx.drawImage(video, box.x, box.y, box.width, box.height, 0, 0, box.width, box.height);
        return c.toDataURL('image/jpeg');
    }

    // Capturar
    captureBtn.addEventListener('click', async () => {
        captureBtn.disabled = true;

        const detection = await faceapi
            .detectSingleFace(video, tinyOptions)
            .withFaceLandmarks()
            .withFaceDescriptor();

        if (!detection) {
            status.textContent = 'No se detectó rostro';
            captureBtn.disabled = false;
            return;
        }

        const descriptor = Array.from(detection.descriptor);
        descriptors.push(descriptor);

        const img = document.createElement('img');
        img.src = createThumb(detection.detection.box);
        thumbs.appendChild(img);

        status.textContent = `Captura ${descriptors.length} registrada`;
        logMsg(`Descriptor ${descriptors.length} guardado`);
        captureBtn.disabled = false;

        if (descriptors.length >= 3) finishBtn.disabled = false;
    });

    // Finalizar
    finishBtn.addEventListener('click', async () => {
        status.textContent = 'Enviando al servidor...';

        const res = await fetch(`/empleados/${idEmpleado}/face`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ descriptors })
        });

        if (!res.ok) {
            const text = await res.text();
            console.error('ERROR SERVIDOR:', text);
            status.textContent = 'Error del servidor (ver consola)';
            return;
        }

        const json = await res.json();


        if (res.ok) {
            status.textContent = 'Registro facial completado correctamente';
            logMsg('Servidor OK');
            finishBtn.disabled = true;
            window.location.href = json.redirect;
        } else {
            status.textContent = 'Error al guardar';
            logMsg(JSON.stringify(json));
        }
    });
});