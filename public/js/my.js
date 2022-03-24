function disabledEventPropagation(event) {
    if (event.stopPropagation) {
        event.stopPropagation();
    } else if (window.event) {
        window.event.cancelBubble = true;
    }
}

window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments)
}

gtag('js', new Date());

gtag('config', 'UA-153115189-2');

window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}

gtag('js', new Date());

gtag('config', 'AW-607164289');

function setShowBtnAction(status) {
    if(status){
        $('#expand-all').hide();
        $('#collapse-all').show();
    }else{
        $('#expand-all').show();
        $('#collapse-all').hide();
    }
}
