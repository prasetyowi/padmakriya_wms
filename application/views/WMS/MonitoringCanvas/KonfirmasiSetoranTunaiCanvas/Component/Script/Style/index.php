<style>
  :root {
    --white: #ffffff;
    --light: #f0eff3;
    --black: #000000;
    --dark-blue: #1f2029;
    --dark-light: #353746;
    --red: #da2c4d;
    --yellow: #f8ab37;
    --grey: #ecedf3;
  }

  .modal-body {
    max-height: calc(100vh - 210px);
    overflow-x: auto;
    overflow-y: auto;
  }

  .error {
    border: 1px solid red;
  }

  .alert-header {
    display: flex;
    flex-direction: row;
  }

  .alert-header .alert-icon {
    margin-right: 10px;
  }

  .span-example .alert-header .alert-icon {
    align-self: center;
  }

  section {
    margin-top: 20px;
    margin-bottom: 20px;
  }

  .check-item {
    transform: scale(1.5);
  }

  .check-item-span {
    margin-left: 7px;
    font-size: 12px;
    font-weight: 600
  }

  #loading-spinner {
    -webkit-transition: all 0.5s ease-in;
    -webkit-animation-name: loading-spin;
    -webkit-animation-duration: 2.0s;
    -webkit-animation-iteration-count: infinite;
    -webkit-animation-timing-function: linear;

    transition: all 0.5s ease-in;
    animation-name: loading-spin;
    animation-duration: 2.0s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
  }

  @keyframes loading-spin {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }


  @-webkit-keyframes loading-spin {
    from {
      -webkit-transform: rotate(0deg);
    }

    to {
      -webkit-transform: rotate(360deg);
    }
  }

  @media only screen and (max-width: 600px) {
    .check-item {
      transform: scale(0.5);
    }
  }

  /* Small devices (portrait tablets and large phones, 600px and up) */
  @media only screen and (min-width: 600px) {
    .check-item {
      transform: scale(0.8);
    }
  }

  /* Medium devices (landscape tablets, 768px and up) */
  @media only screen and (min-width: 768px) {

    .check-item {
      transform: scale(1);
    }
  }

  /* Large devices (laptops/desktops, 992px and up) */
  @media only screen and (min-width: 992px) {}
</style>