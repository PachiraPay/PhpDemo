import 'jquery'

var fctIsNumber =   function (evt) {
 // evt = (evt) ? evt : window.event;

 console.log(evt.which) 
 console.log(evt) 
    console.log(this.value) 
    /*var charCode = (evt.which) ? evt.which : evt.keyCode;
    console.log(charCode) 
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;*/
}




var element = $("#payment_information_ccv");
$(element).on('input', fctIsNumber);