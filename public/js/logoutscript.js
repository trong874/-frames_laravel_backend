$('#submit_logout').on('click',function (e) {
    e.preventDefault();
    localStorage.removeItem('token_jwt')
    $('#form_logout').submit();
})
