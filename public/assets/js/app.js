/*
* Drag and drop system
*/

sortable('.js-sortable', {
    forcePlaceholderSize: true,
    placeholderClass: 'mb1 bg-navy border border-yellow',
    // hoverClass: 'bg-maroon yellow',
    itemSerializer: function (item, container) {
        item.parent = '[parentNode]'
        item.node = '[Node]'
        item.html = item.html.replace('<','&lt;')
        return item
    },
    containerSerializer: function (container) {
        container.node = '[Node]'
        return container
    }
})
sortable('.js-sortable-copy', {
    forcePlaceholderSize: true,
    copy: true,
    acceptFrom: false,
    placeholderClass: 'mb1 bg-navy border border-yellow',
});
sortable('.js-sortable-copy-target', {
    forcePlaceholderSize: true,
    acceptFrom: '.js-sortable-copy,.js-sortable-copy-target',
    placeholderClass: 'mb1 border border-maroon',
});
sortable('.js-grid', {
    forcePlaceholderSize: true,
    placeholderClass: 'col col-4 border border-maroon'
});
sortable('.js-sortable-connected', {
    forcePlaceholderSize: true,
    connectWith: '.js-connected',
    handle: '.js-handle',
    items: 'li',
    placeholderClass: 'border border-white bg-orange mb1'
});
sortable('.js-sortable-inner-connected', {
    forcePlaceholderSize: true,
    connectWith: 'js-inner-connected',
    handle: '.js-inner-handle',
    items: '.item',
    maxItems: 3,
    placeholderClass: 'border border-white bg-orange mb1'
});
/**************************************
* ENABLE TO Debug Drag and drop System*
**************************************/
// document.querySelector('.js-sortable-connected').addEventListener('sortupdate', function(e){
//     console.log('Sortupdate: ', e.detail);
//     console.log('Container: ', e.detail.origin.container, ' -> ', e.detail.destination.container);
//     console.log('Index: '+e.detail.origin.index+' -> '+e.detail.destination.index);
//     console.log('Element Index: '+e.detail.origin.elementIndex+' -> '+e.detail.destination.elementIndex);
// });
//
// document.querySelector('.js-sortable-connected').addEventListener('sortstart', function(e){
//     console.log('Sortstart: ', e.detail);
// });
//
// document.querySelector('.js-sortable-connected').addEventListener('sortstop', function(e){
//     console.log('Sortstop: ', e.detail);
// });
//
// sortable('.js-sortable-buttons', {
//     forcePlaceholderSize: true,
//     items: 'li',
//     placeholderClass: 'border border-white mb1',
//     hoverClass: 'bg-yellow'
// });
// // buttons to add items and reload the list
// // separately to showcase issue without reload
// document.querySelector('.js-add-item-button').addEventListener('click', function(){
//     doc = new DOMParser().parseFromString(`<li class="p1 mb1 blue bg-white">new item</li>`, "text/html").body.firstChild;
//     document.querySelector('.js-sortable-buttons').appendChild(doc);
// });
//
// document.querySelector('.js-reload').addEventListener('click', function(){
//     console.log('Options before re-init:');
//     console.log(document.querySelector('.js-sortable-buttons').h5s.data.opts);
//     sortable('.js-sortable-buttons');
//     console.log('Options after re-init:');
//     console.log(document.querySelector('.js-sortable-buttons').h5s.data.opts);
// });
// // JS DISABLED
// document.querySelector('.js-disable').addEventListener('click', function(){
//     var $list = document.querySelector('[data-disabled]');
//     if ( $list.getAttribute('data-disabled') === 'false' ) {
//         this.innerHTML = 'Enable';
//         sortable($list, 'disable');
//         $list.setAttribute('data-disabled', true);
//         $list.classList.add('muted');
//     } else {
//         this.innerHTML = 'Disable';
//         sortable($list, 'enable');
//         $list.setAttribute('data-disabled', false);
//         $list.classList.remove('muted');
//     }
// });
//
// // Destroy & Init
// document.querySelector('.js-destroy').addEventListener('click', function(){
//     sortable('.js-sortable-buttons', 'destroy');
// });
// document.querySelector('.js-init').addEventListener('click', function(){
//     sortable('.js-sortable-buttons', {
//         forcePlaceholderSize: true,
//         items: 'li',
//         placeholderClass: 'border border-white mb1'
//     });
// });

/***************************
* End drag and drop system *
***************************/
let oldXHR = window.XMLHttpRequest;

function newXHR()
{
    let realXHR = new oldXHR();

    const notShowLoaderWhen = [
        'selectResponsible',
        'findWallUsers',
        'alertasNif',
        'alertasOffice',
        'findAlertOffice',
        'alertas',
    ];

    realXHR.addEventListener('readystatechange', function () {
        let url   = realXHR.responseURL;
        let entry = true;
        notShowLoaderWhen.forEach(function callBack(currentValue)
        {
            if (url.indexOf(currentValue) !== -1) {
                entry = false
            }
        });
        if (entry) {
            if (realXHR.readyState === 2) {
                $('.loader').show();
            }
            if (realXHR.readyState === 4) {
                $('.loader').hide();
            }
        } else {
            $('.loader').hide();
        }
    }, false);

    return realXHR;
}

window.XMLHttpRequest = newXHR;

window.addEventListener("beforeunload", function () {
    $('.loader').show();
});

window.addEventListener("load", function () {
    $('.loader').hide();
});
