var menuItem = document.querySelectorAll('.item_menu');
var btnExp = document.querySelector('#btn-exp');
var menuSide = document.querySelector('.menu_lateral');
var main = document.querySelector('main');

// Seleciona o item ativo
function selectLink() {
    menuItem.forEach((item) =>
        item.classList.remove('ativo')
    );
    this.classList.add('ativo');
}

// menuItem.forEach((item) =>
//     item.addEventListener('click', selectLink)
// );

// Expande o menu e ajusta o conteúdo
btnExp.addEventListener('click', function () {
    menuSide.classList.toggle('expandir');
    main.classList.toggle('expandido');
});
