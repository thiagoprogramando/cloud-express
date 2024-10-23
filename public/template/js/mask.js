function mascaraReal(input) {
    let value = input.value;
    
    if (value === '') {
        input.value = '0,00';
        return;
    }
    
    value = value.replace(/\D/g, '');
    value = (parseInt(value) / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

    input.value = value;
}