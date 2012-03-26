<div class="slot1-inner">
  <div id="carousel-holder">
    <span class="carousel_next ir"><a href="#" class="button_carousel_next" title="Next">Next</a></span>
    <span class="carousel_previous ir"><a href="#" class="button_carousel_previous" title="Previous">Prev</a></span>
    <ul id="sample-roundabout">
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_1.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_4.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time.</p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_1.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_2.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_3.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_4.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_3.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_2.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>
      <li>
        <div class="wrapper">
          <img src="/images/frontend/mockups/carousel_1.jpg" alt="">
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2>Too Much Time on His Hands</h2>
              <p>Almost none of Stephen's 200 clocks are set to the same time. It's enough to drive one cuckoo! <a href="">Watch the interview now.</a></p>
            </div>
          </div>
        </div>
      </li>

    </ul>
  </div><!--/#carousel-holder-->
</div><!--/.slot1-inner-->

<script>
  $(document).ready(function()
  {
    $("#sample-roundabout").roundabout({
      responsive: true,
      tilt: 0.6,
      minScale: 0.6,
      minOpacity: 1,
      duration: 400,
      easing: 'easeOutQuad',
      enableDrag: true,
	    responsive: true,
      dropEasing: 'easeOutBounce',
      btnNext: '.button_carousel_next',
      btnPrev: '.button_carousel_previous'
    }, function() {
      $(this).fadeTo(500, 1);
    });
  });
</script>
