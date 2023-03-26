

    // Currency Separator
    let commaCounter = 10;

    function numberSeparator(_number) {
        _number += '';

       

        for (let i = 0; i < commaCounter; i++) {
            _number = _number.replace(' ', '');
        }

        x = _number.split('.');
        y = x[0];
        z = x.length > 1 ? '.' + x[1] : '';
        let rgx = /(\d+)(\d{3})/;

        while (rgx.test(y)) {
            y = y.replace(rgx, '$1' + ' ' + '$2');
        }
        commaCounter++;
        return y + z;
    }


    function convertNumber(number, convert = true, round = false) {
       
        let val = parseFloat(String(number).replace(/\s/g, ''));
        if (convert) {
            
            if (round) {
                val = Math.round(val);
            } else {
                val = Math.ceil(val);
            }
          
        }
       
        if (isNaN(val)) {
            return '';
        }
        return val;
    }

    function setValue(number, convert = true, round = false) {
        return numberSeparator(convertNumber(number, convert, round));
    }

    // Set Currency Separator to input fields
    $(document).on('keypress , paste', '.input-money', function(e) {
        if (/^-?\d*[,.]?(\d{0,3},)*(\d{3},)?\d{0,3}$/.test(e.key)) {
            $('.input-money').on('input', function() {
                e.target.value = numberSeparator(e.target.value);
            });
        } else {
            e.preventDefault();
            return false;
        }
    });

  

