<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modal com X Magnético</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      overflow: hidden;
    }

    header {
      position: fixed;
      top: 0;
      width: 100%;
      height: 60px;
      background: #007BFF;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .overlay_modal_menu_central {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;  
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 10;
    }

    .modal_menu_central {
      width: 300px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      padding: 20px;
      position: absolute;
      text-align: left;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .modal_close_button {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
      color: #000;
      background: transparent;
      border: none;
    }

    .modal_menu_central h2 {
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 18px;
    }

    .modal_links {
      list-style-type: none;
      padding: 0;
    }

    .modal_links li {
      margin: 10px 0;
    }

    .modal_links a {
      color: #007BFF;
      text-decoration: none;
    }

    .hidden_overlay_modal_menu_central {
      opacity: 0;
      pointer-events: none;
    }

    .highlight-border {
      box-shadow: 0 0 20px 5px rgba(0, 255, 0, 0.7);
    }

    #close-target {
      position: absolute; /* Certifique-se de que o elemento pode ser movido */
      bottom: 20px;
      left: 20px;
      width: 80px;
      height: 80px;
      background-color: rgba(255, 0, 0, 0.5);
      color: white;
      font-size: 24px;
      font-weight: bold;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 20;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    #close-target.visible {
      opacity: 0.5;
    }

    #close-target.attracting {
      background-color: black;  /* Fundo preto */
      box-shadow: 0 0 20px 5px rgba(255, 0, 0, 0.8);  /* Sombra vermelha */
    }

    .feedback-closing {
      box-shadow: 0 0 20px 5px rgba(255, 0, 0, 0.8);
      border: 2px solid rgba(255, 0, 0, 0.8);
    }
  </style>
</head>
<body>
  <header>
  <div class="menu-button" onclick="toggleModal()">☰</div>
</header>

<div class="overlay_modal_menu_central hidden_overlay_modal_menu_central" id="overlay_modal_menu_central" onclick="closeModal()">
  <div class="modal_menu_central" id="modal_menu_central" onclick="event.stopPropagation()">
    <button class="modal_close_button" onclick="closeModal(event)">×</button>
    <br>
    <hr>
    <h2>Para me fechar, arraste o (x) sobre mim.</h2>
    <hr>
    <ul class="modal_links">
      <li><a href="https://linktudo.com.br/index2___anterior___modelo_antigo___legado.php" target="_blank">Index anterior (Legado)</a></li>
      <li><a href="#">Exemplo 2</a></li>
      <li><a href="#">Exemplo 3</a></li>
      <li><a href="#">Exemplo 4</a></li>
    </ul>
  </div>
</div>

<div id="close-target">X</div>

<script>
  const modal = document.getElementById("modal_menu_central");
  const overlay = document.getElementById("overlay_modal_menu_central");
  const closeTarget = document.getElementById("close-target");

  let offsetX = 0, offsetY = 0, startX = 0, startY = 0;
  let isDraggingModal = false, isDraggingTarget = false, isOverlapping = false;
  
  function toggleModal() {
      overlay.classList.contains("hidden_overlay_modal_menu_central") ? openModal() : closeModal();
    }

  function openModal() {
    overlay.classList.remove("hidden_overlay_modal_menu_central");
    closeTarget.classList.add("visible");
    modal.style.left = `${(window.innerWidth - modal.offsetWidth) / 2}px`;
    modal.style.top = `${(window.innerHeight - modal.offsetHeight) / 2}px`;
    modal.classList.remove("feedback-closing");
  }

  function closeModal() {
    overlay.classList.add("hidden_overlay_modal_menu_central");
    closeTarget.classList.remove("visible");
    modal.classList.remove("feedback-closing");
    // Limpa os estilos aplicados diretamente aos elementos, após fechar o modal
    overlay.style.cssText = '';  
    modal.style.cssText = '';
  }

  function startDragModal(e) {
    e.preventDefault();
    isDraggingModal = true;
    const rect = modal.getBoundingClientRect();
    startX = (e.type === "touchstart" ? e.touches[0].clientX : e.clientX) - rect.left;
    startY = (e.type === "touchstart" ? e.touches[0].clientY : e.clientY) - rect.top;
    modal.style.transition = "none";
  }

  function handleDragModal(e) {
    if (!isDraggingModal) return;
    const clientX = e.type === "touchmove" ? e.touches[0].clientX : e.clientX;
    const clientY = e.type === "touchmove" ? e.touches[0].clientY : e.clientY;

    offsetX = clientX - startX;
    offsetY = clientY - startY;

    modal.style.left = `${offsetX}px`;
    modal.style.top = `${offsetY}px`;

    checkOverlap();
  }

  function stopDragModal() {
    isDraggingModal = false;
    modal.style.transition = "transform 0.2s ease";
  }

  function startDragTarget(e) {
    e.preventDefault();
    isDraggingTarget = true;
    const rect = closeTarget.getBoundingClientRect();
    startX = (e.type === "touchstart" ? e.touches[0].clientX : e.clientX) - rect.left;
    startY = (e.type === "touchstart" ? e.touches[0].clientY : e.clientY) - rect.top;
    closeTarget.classList.add("attracting");
  }






  function handleDragTarget(e) {
  if (!isDraggingTarget) return;
  
  const clientX = e.type === "touchmove" ? e.touches[0].clientX : e.clientX;
  const clientY = e.type === "touchmove" ? e.touches[0].clientY : e.clientY;


  offsetX = clientX - startX;
  offsetY = clientY - startY;

  closeTarget.style.left = `${offsetX}px`;
  closeTarget.style.top = `${offsetY}px`;


  checkOverlap();  // Verifica se a bolinha está sobrepondo o modal
  closeTarget.classList.add("attracting");

}


  function stopDragTarget() {
    isDraggingTarget = false;
    closeTarget.style.transition = "transform 0.2s ease";
    closeTarget.style = "close-target";
    closeTarget.classList.remove("attracting");


    
    if (isOverlapping) {
      closeTarget.classList.remove("attracting");

      closeModal();
    }
  }

  function checkOverlap() {
    const modalRect = modal.getBoundingClientRect();
    const targetRect = closeTarget.getBoundingClientRect();

    if (
      modalRect.left < targetRect.right &&
      modalRect.right > targetRect.left &&
      modalRect.top < targetRect.bottom &&
      modalRect.bottom > targetRect.top
    ) {
      closeTarget.classList.add("attracting");
      modal.classList.add("feedback-closing");
      isOverlapping = true;
    } else {
      closeTarget.classList.remove("attracting");
      modal.classList.remove("feedback-closing");
      isOverlapping = false;
    }
  }

  modal.addEventListener("mousedown", startDragModal);
  modal.addEventListener("mousemove", handleDragModal);
  modal.addEventListener("mouseup", stopDragModal);
  modal.addEventListener("touchstart", startDragModal);
  modal.addEventListener("touchmove", handleDragModal);
  modal.addEventListener("touchend", stopDragModal);

  closeTarget.addEventListener("mousedown", startDragTarget);
  closeTarget.addEventListener("mousemove", handleDragTarget);
  closeTarget.addEventListener("mouseup", stopDragTarget);
  closeTarget.addEventListener("touchstart", startDragTarget);
  closeTarget.addEventListener("touchmove", handleDragTarget);
  closeTarget.addEventListener("touchend", stopDragTarget);

</script>
<!-- criado com auxilio de ia -->
</body>
</html>
