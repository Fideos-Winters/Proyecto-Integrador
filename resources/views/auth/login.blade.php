<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" >
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet"  />
</head>

<body class="h-screen w-screen bg-[#010e6b] flex items-center justify-center">

  <div class="w-[70%] h-[70%] bg-white rounded-xl shadow-lg flex overflow-hidden">
    
    <!-- Sección de imagen -->
    <div class="w-1/2 bg-gradient-to-b from-[#010e6b] via-[#be74be] via-[#d481d2] to-[#703b94] flex items-center justify-center">
      <img src="http://admin.umbrellastella.com/assets/iconos/logo.png" alt="Login Image" class="rounded-lg shadow-lg max-h-[80%]">
    </div>

    <!-- Sección de formulario -->
    <div class="w-1/2 flex items-center justify-center p-10">
      <form id="loginForm" class="w-full max-w-md space-y-6">
        <h2 class="text-3xl font-bold text-gray-800 text-center">Iniciar Sesión</h2>

        <!-- Mensaje de error -->
        <div id="errorMsg" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg text-sm text-center">
          Usuario o contraseña incorrectos.
        </div>
        
        <!-- Usuario -->
        <div>
          <label for="usuario" class="block mb-2 text-sm font-medium text-gray-700">Usuario</label>
          <input type="text" id="usuario" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#be74be] focus:border-[#be74be] block w-full p-2.5" placeholder="Tu usuario" required>
        </div>

        <!-- Contraseña -->
        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Contraseña</label>
          <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#d481d2] focus:border-[#d481d2] block w-full p-2.5" placeholder="••••••••" required>
        </div>

        <!-- Botón -->
        <button type="submit" id="btnLogin" class="w-full text-white bg-[#703b94] hover:bg-[#be74be] focus:ring-4 focus:outline-none focus:ring-[#d481d2] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
          Entrar
        </button>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

  <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const usuario    = document.getElementById('usuario').value;
      const contrasena = document.getElementById('password').value;
      const errorMsg   = document.getElementById('errorMsg');
      const btnLogin   = document.getElementById('btnLogin');

      // Limpiar error y deshabilitar botón
      errorMsg.classList.add('hidden');
      btnLogin.disabled = true;
      btnLogin.textContent = 'Entrando...';

      try {
        const response = await fetch('/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ usuario, contrasena }),
        });

        const data = await response.json();

      if (response.ok) {
         // En vez de localStorage, llamamos a una ruta web que guarda en sesión
         const sessionRes = await fetch('/guardar-sesion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ token: data.access_token, usuario: data.usuario }),
         });

    window.location.href = '/inicio';
        } else {
          errorMsg.textContent = data.message || 'Usuario o contraseña incorrectos.';
          errorMsg.classList.remove('hidden');
        }

      } catch (error) {
        errorMsg.textContent = 'Error de conexión. Intenta de nuevo.';
        errorMsg.classList.remove('hidden');
      } finally {
        btnLogin.disabled = false;
        btnLogin.textContent = 'Entrar';
      }
    });
  </script>

</body>
</html>