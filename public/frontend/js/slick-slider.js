$('.feature-slider').slick({
  infinite: true,
  slidesToShow: 4,
  slidesToScroll: 4,

  responsive: [
    {
      breakpoint: 1660,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 3,
        infinite: true,
      }
    },
    {
      breakpoint: 1399,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 1199,
      settings: {
        slidesToShow: 2.5,
        slidesToScroll: 1
      }
    },

    {
      breakpoint: 991,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },

    {
      breakpoint: 676,
      settings: {
        slidesToShow: 1.5,
        slidesToScroll: 1
      }
    },

    {
      breakpoint: 425,
      settings: {
        slidesToShow: 1.2,
        slidesToScroll: 1
      }
    },

    {
      breakpoint: 375,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});