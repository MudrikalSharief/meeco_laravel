<x-layout>
    <div id="paymongo-page" class=" absolute top-0 left-0  flex justify-center items-center h-screen w-full">
        <div id="success_loader" class=" alert-popup-container w-full">
            <div class="success-checkmark">
                <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="icon-circle"></div>
                        <div class="icon-fix"></div>
                </div>
            </div>
            <div class="alert-popup-title">Subcribed Successfully!!</div>
                <div class="alert-popup-message">
                    Closing in <span class="countdown">5</span> .
                </div>
                {{-- <div class="alert-popup-confirm">
                    <button class="button">OK</button>
                </div> --}}
            </div>
        </div>    
    </div>  
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const botnav = document.getElementById('bottom_nav');
            botnav.classList.add('hidden');
            //for animation
            const success_loader = document.getElementById('success_loader');




            // Check if we are on the paymongo page
            const thispage = document.getElementById('paymongo-page');
            if (thispage) {
                // Retrieve the checkout URL from local storage
                const checkoutID = localStorage.getItem('checkoutID');
                const promoID = localStorage.getItem('promoID');
                console.log('checkoutID URL:', checkoutID);

                fetch('/checkpayment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ checkoutID: checkoutID, promoID: promoID })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Payment verification response:', data);
                    if (data.success) {
                        success_loader.classList.remove('hidden');

                        // Countdown from 5 to 1
                        let countdown = 3;
                        const countdownElement = document.querySelector('.countdown');
                        countdownElement.textContent = countdown;
                        const countdownInterval = setInterval(() => {
                            countdown--;
                            countdownElement.textContent = countdown;
                            if (countdown === 1) {
                                clearInterval(countdownInterval);
                                localStorage.removeItem('checkoutID');
                                localStorage.removeItem('promoID');
                                window.location.href = 'http://127.0.0.1:8000/upgrade'; // Redirect to home page after countdown
                            }
                        }, 1000);

                    } else {
                        alert('Payment failed. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                });
            }
        });
    </script>
    <style>
        .alert-popup-container {
        text-align: center;
        }

        .alert-popup-title {
        font-size: 30px;
        font-weight: bold;
        color: #4CAF50;
        margin-top: 15px;
        margin-bottom: 15px;
        z-index: 2;
        position: relative;
        }

        .alert-popup-message {
        color: #777;
        font-size: 21px;
        font-weight: 300;
        line-height: 1.4;
        }

        .alert-popup-confirm {
        margin-top: 20px;
        margin-bottom: 20px;
        }

        .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto;

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;

            &::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
            }

            &::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
            }

            &::before,
            &::after {
            content: '';
            height: 100px;
            position: absolute;
            background: #FFFFFF;
            transform: rotate(-45deg);
            z-index: 2;
            }

            .icon-line {
            height: 5px;
            background-color: #4CAF50;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;

            &.line-tip {
                top: 46px;
                left: 14px;
                width: 25px;
                transform: rotate(45deg);
                animation: icon-line-tip 0.75s;
            }

            &.line-long {
                top: 38px;
                right: 8px;
                width: 47px;
                transform: rotate(-45deg);
                animation: icon-line-long 0.75s;
            }
            }

            .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 175, 80, .5);
            }

            .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: #FFFFFF;
            }
        }
        }

        @keyframes rotate-circle {
        0% {
            transform: rotate(-45deg);
        }

        5% {
            transform: rotate(-45deg);
        }

        12% {
            transform: rotate(-405deg);
        }

        100% {
            transform: rotate(-405deg);
        }
        }

        @keyframes icon-line-tip {
        0% {
            width: 0;
            left: 1px;
            top: 19px;
        }

        54% {
            width: 0;
            left: 1px;
            top: 19px;
        }

        70% {
            width: 50px;
            left: -8px;
            top: 37px;
        }

        84% {
            width: 17px;
            left: 21px;
            top: 48px;
        }

        100% {
            width: 25px;
            left: 14px;
            top: 45px;
        }
        }

        @keyframes icon-line-long {
        0% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        65% {
            width: 0;
            right: 46px;
            top: 54px;
        }

        84% {
            width: 55px;
            right: 0px;
            top: 35px;
        }

        100% {
            width: 47px;
            right: 8px;
            top: 38px;
        }
        }

    </style>
</x-layout>
