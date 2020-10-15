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

    function load(timeout) {
        setTimeout(() => {
            window.location.reload();
        }, timeout);
    }
    let timer = null;
    let time = null;
    let percentage = 0;

    $('form#form-email').submit((e) => {
        e.preventDefault();
        let txtEmail = $('#email').val();
        var files1 = $('#txtemail')[0].files[0];
        let subject = $('#subject').val();
        let content = tinymce.activeEditor.getContent();
        let htmlContent = $('#htmlContent')[0].files[0];
        let username = $('#username').val()
        let password = $('#password').val()
        let bidanh = $('#bidanh').val()
        let formdata = new FormData();
        if (subject == "" || username == "" || password == "" || bidanh == "") {
            alert('con thieu gi do');
        } else {
            if (!ValidateEmail(username)) {
                toastr['error']('sai email gửi');
            } else {
                formdata.append('subject', subject)
                formdata.append('content', content)
                formdata.append('txtEmail', txtEmail)
                formdata.append('files', files1)
                formdata.append('htmlContent', htmlContent)
                formdata.append('username', username)
                formdata.append('password', password)
                formdata.append('bidanh', bidanh)
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
                    beforeSend: function() {
                        // $('#loading').css('display', 'block');
                        // $('.bg').css('display', 'block');
                        $('button').addClass('disabled');
                        timer = setInterval(function() {
                            percentage += 20
                            progress_bar_process(percentage);
                        }, 1000);
                    },
                    success: (res) => {

                        $('button').removeClass('disabled');

                        if (res.status == 1) {

                            if (res.messages.includes('SMTP Error: Could not authenticate.')) {
                                window.open("https://myaccount.google.com/lesssecureapps", "", "top=100, left=200, width=900,height=500");
                                window.open("https://accounts.google.com/DisplayUnlockCaptcha", "", "top=100, left=200, width=900,height=500");

                                toastr["error"]('Sai mật khẩu hoặc chưa Cho phép ứng dụng kém an toàn trên Gmail.')
                            } else {
                                toastr["error"](res.messages)
                            }

                        } else {
                            $('#sended').text(res.mail.length);
                            for (let i = 0; i < res.mail.length; i++) {
                                setTimeout(() => {
                                    toastr["success"]('Gửi mail thành công đến ' + res.mail[i])
                                    $('#mail-sender').val(res.mail);

                                }, i * 1000);

                            }


                        }

                    },

                })
            }



        }


    })

    function progress_bar_process(percentage) {

        // document.getElementById('process').style.display = 'block';
        document.getElementsByClassName('progress-bar')[0].style.width = percentage + '%';
        document.getElementsByClassName('progress-bar')[0].innerText = percentage + "%";
        if (percentage > 100) {
            clearInterval(timer);
            document.getElementsByClassName('progress-bar')[0].style.width = '0%';

            document.getElementById('process').style.display = 'none'
        }
    }
    $('input[type=file]').change((e) => {
        var fileName = e.target.files[0].name;

        toastr['success']('upload ' + fileName)

    })

    function ValidateEmail(mail) {
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail)) {
            return true
        }

        return false
    }
    $('#email').keyup(() => {
        let text = $('#email').val()
        if (text !== "") {
            $('#txtemail').css('display', 'none')
            $('label[for=txtemail]').css('display', 'none')
        } else {
            $('#txtemail').css('display', 'block')
            $('label[for=txtemail]').css('display', '')
        }
    })
    $('#email').change((e) => {
        var fileName = e.target.files[0].name;

        toastr['success']('upload ' + fileName)
        if (fileName !== "") {
            $('#email').css('display', 'none')
        } else {
            $('#email').css('display', 'block')
        }

    })

})