$(document).ready(() => {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    let timer = null;
    let time = null;
    let percentage = 0;
    $('form#form-email').submit((e) => {
        e.preventDefault();
        let txtEmail = $('#email').val();
        var files1 = $('#txtemail')[0].files[0];
        let subject = $('#subject').val();
        let content = $('#content').val();
        let htmlContent = $('#htmlContent')[0].files[0];
        let formdata = new FormData();
        if (subject === "") {
            alert('con thieu gi do');
        } else {
            formdata.append('subject', subject)
            formdata.append('content', content)
            formdata.append('txtEmail', txtEmail)
            formdata.append('files', files1)
            formdata.append('htmlContent', htmlContent)
            var totalfiles = $('#select_image')[0].files.length;

            for (let i = 0; i <= totalfiles; i++) {
                formdata.append('select_image[]', $('#select_image')[0].files[i])

            }
            $.ajax({

                url: './server/emailv2.php',
                type: 'post',
                processData: false,
                contentType: false,
                data: formdata,
                dataType: 'json',
                beforeSend: function () {
                    // $('#loading').css('display', 'block');
                    // $('.bg').css('display', 'block');
                    $('button').addClass('disabled');
                    timer = setInterval(function () {
                        percentage += 20
                        progress_bar_process(percentage);
                    }, 1000);
                },
                success: (res) => {
                    $('#loading').css('display', 'none');
                    $('button').removeClass('disabled');
                    $('.bg').css('display', 'none');

                    $('#sended').text(res.mail.length);
                    

                    if (res.status == 1) {
                        toastr["error"](res.messages)

                    } else {

                        for (let i = 0; i < res.mail.length; i++) {
                            setTimeout(() => {
                                toastr["success"]('Gửi mail thành công đến ' + res.mail[i])
                                $('#mail-sender').val(res.mail);

                            }, i * 1000);
                        }

                    }

                },
                resetForm: true
            })

        }


    })

    function progress_bar_process(percentage) {
        document.getElementById('process').style.display = 'block'
        document.getElementById('process').style.display = 'block';
        document.getElementsByClassName('progress-bar')[0].style.width = percentage + '%';
        document.getElementsByClassName('progress-bar')[0].innerText = percentage + "%";
        if (percentage > 100) {
            clearInterval(timer);
            document.getElementsByClassName('progress-bar')[0].style.width = '0%';

            document.getElementById('process').style.display = 'none'
        }
    }

})