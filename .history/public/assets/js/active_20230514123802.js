$(function () {

  
   

$('body').on('click', '.test', function (e) {
    e.preventDefault();
    const icon = this.querySelector('i');
    const class_ = this;
    const url = this.href;

    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function (response, status) {
        console.log(icon);

            if (response.code === 200) {
                if (class_.classList.contains('btn-main')) {

                   
                    class_.classList.replace('btn-main', 'btn-danger');
                    class_.classList.replace('btn-danger', 'btn-main');
                //points.classList.replace('bg-danger','bg-success');
                    }
                else {

                            class_.classList.replace('btn-danger', 'btn-main');
                        //points.classList.replace('bg-success','bg-danger');
                }

                if (icon.classList.contains('fa-unlock-alt')){
                     icon.classList.replace('fa-unlock-alt','fa-lock');
                }
                else {
                    icon.classList.replace('fa-lock', 'fa-unlock-alt');
                }

            }
            //     

           

            //  if(response.active==true){
            //      active.textContent="Activé";
            //      points.classList.replace('bg-danger','bg-success');
            //  }else{
            //      active.textContent="Désactivé";
            //      points.classList.replace('bg-success','bg-danger');
            //  }

            //     },
            //     error :function(error)
            //     {
            //         console.log(error);
        }
    });
});

  });


// function onClickBtnActive(event) {
//     event.preventDefault();
//     const icon = this.querySelector('i');
//     const class_ = this;

//     const active = this.closest("tr").querySelector('.active > .el');
//     const ligne = this.closest("tr");
//     const points= this.closest("tr").querySelector('.active > .legend-indicator');
//      console.log(points.classList);
//     const url = this.href;
//     console.log(icon.classList.value);
//      alert()
//     $.ajax({
//         url: url,
//         type: 'get',
//         dataType: 'json',
//         success: function (response, status) {
//             if (response.code === 200) {
//                  ligne?.remove();
//             }
//             if(class_.classList.contains('btn-outline-success')){
//                 class_.classList.replace('btn-outline-success','btn-outline-danger');

//             }else{
//                 class_.classList.replace('btn-outline-danger','btn-outline-success');
//             }


//             if(icon.classList.contains('tio-stop-circle')){

//                 icon.classList.replace('tio-stop-circle','tio-checkmark-circle');
//                 class_.classList.replace('btn-outline-danger','btn-outline-success');
//                 points.classList.replace('bg-danger','bg-success');
//             }
//             else {

//                 icon.classList.replace('tio-checkmark-circle','tio-stop-circle');

//                 class_.classList.replace('btn-outline-success','btn-outline-danger');
//                 points.classList.replace('bg-success','bg-danger');
//             }

//             if(response.active==1){
//                  active.textContent="Activé";
//                  points.classList.replace('bg-danger','bg-success');
//              }else{
//                  active.textContent="Désactivé";
//                  points.classList.replace('bg-success','bg-danger');
//              }

//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// }
// document.querySelectorAll('a.activer').forEach(function (link) {
//     link.addEventListener('click', onClickBtnActive);
// })