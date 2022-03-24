let price_old = $('#price_old');
let percent_sale = $('#percent_sale');
let price = $('#price');
let display_value = {
    price_old:'',
    percent_sale:'',
    price:'',
};

price_old.on('keyup',todo);
percent_sale.on('keyup',todo);


function todo() {
    calc();
    setDisplay()
}
function calc() {
    let price_old_value = price_old.val();
    let percent_sale_value = percent_sale.val();
    if (percent_sale_value <= 0 || percent_sale_value ===''){
        percent_sale.val(0);
        percent_sale_value = 0
    }
    if (percent_sale_value > 0){
        percent_sale_value = percent_sale_value.replace(/^0+/, '');
        percent_sale.val(percent_sale_value.replace(/^0+/, ''));
    }
    if(percent_sale_value > 100){
        percent_sale.val(100)
        percent_sale_value = 100;
    }
    let price_percent_sale = price_old_value*percent_sale_value/100;
    let price_sale = price_old_value - price_percent_sale;
    if (price_sale <= 0){
        price_sale = 0
    }
    price.val(Math.round(price_sale));
    display_value.price_old = price_old_value;
    display_value.percent_sale = percent_sale_value;
    display_value.price = Math.round(price_sale);
}

function setDisplay() {
    var price_old = display_value.price_old;
    var percent_sale = display_value.percent_sale;
    var price = display_value.price;
    $('#display_price_old').html(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price_old))
    $('#display_percent_sale').html(percent_sale+'%')
    $('#display_price').html(new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price))
}
