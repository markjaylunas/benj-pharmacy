<footer>
            <div class="upper-footer">
                <section>
                    <h4>Important Notice</h4>
                    <p>We only deliver locally in Laurel, Batangas.</p>
                    <hr>
                    <p>You might experience some delays in the delivery of your orders, especially in provincial areas if there is a surge in demand or due to inclement weather. </p>
                </section>
                <section>
                    <h4>Contact Us</h4>
                    <p>If you have concerns, questions or suggestions on our product offerings, you may reach us at</p>
                    <h4>Online Customer Service Hotline:</h4>
                    <p>(+63) 901-234-5678</p>
                    <p>(+63) 901-234-5678</p>
                </section>
                <section>
                    <h4>Follow Us</h4>
                    <div class="social">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                    <div class="payment-method">
                        <h4>Payment Method</h4>
                        <div class="payment-logo">
                        <p>COD</p>
                            <img src="https://logos-download.com/wp-content/uploads/2020/06/GCash_Logo-700x618.png" alt="">
                        </div>
                    </div>
                </section>
            </div>
            <div class="lower-footer">
                <p>&copy; 2021 Benj Pharmacy. All rights reserved</p>
            </div>
        </footer>
    <!-- JS FILES -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <script>
        $(document).ready(function() {
            $('.clickable').click(function(){
                window.location = $(this).find('a').attr('href');
            })
            $('.minus_one').click(function(){
                $(this).next().val(parseInt($(this).next().val())-1);
            })
            $('.add_one').click(function(){
                $(this).prev().val(parseInt($(this).prev().val())+1);
            })
            // // If cookie is set, scroll to the position saved in the cookie.
            // if ( $.cookie("scroll") !== null ) {
            //     $(document).scrollTop( $.cookie("scroll") );
            // }
            // $('.scroll_back').on("click", function() {
            //     // Set a cookie that holds the scroll position.
            //     print("scroll");
            //     $.cookie("scroll", $(document).scrollTop() );
            // });

            
        })
    </script>
    <script scr="./js/main.js"></script>
</body>
</html>