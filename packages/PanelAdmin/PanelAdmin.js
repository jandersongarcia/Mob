const fullscreenBtn = document.getElementById('FullscreenBtn');
const fullscreenIco = document.getElementById('fullscreenIco');
const btnMenu = document.getElementById('btnMenu');

fullscreenBtn.addEventListener('click', () => {

  const element = document.documentElement;

  if (!document.fullscreenElement) {
    element.requestFullscreen().then(() => {
      fullscreenIco.classList.remove('bi-arrows-fullscreen');
      fullscreenIco.classList.add('bi-fullscreen-exit');
    });
  } else {
    document.exitFullscreen().then(() => {
      fullscreenIco.classList.remove('bi-fullscreen-exit');
      fullscreenIco.classList.add('bi-arrows-fullscreen');
    });
  }

})

btnMenu.addEventListener('click', function () {
  const lateralMenu = document.getElementById('lateralMenu');
  console.log(lateralMenu.style.left)
  if (lateralMenu.style.left === '0px') {
    lateralMenu.style.left = '-250px';
  } else {
    lateralMenu.style.left = '0px';
  }
});