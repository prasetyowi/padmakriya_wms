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

	.text-bold {
		font-weight: bold;
	}

	.styling-checkbox {
		transform: scale(1.5);
	}

	.custom-form-control {
		padding: 7px;
		text-align: center;
		border-radius: 5px;
		width: 60%;
	}

	/* .dataTables_paginate .previous,
	.dataTables_paginate .next {
		display: none;
	} */

	.bg-transparent {
		background: transparent !important;
	}

	.border-0 {
		border: none !important;
	}

	#selectCamera {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	input[name="tools"]:checked,
	input[name="tools"]:not(:checked) {
		position: absolute;
		left: -9999px;
		width: 0;
		height: 0;
		visibility: hidden;
	}

	#selectCamera .checkbox-tools:checked+label,
	#selectCamera .checkbox-tools:not(:checked)+label {
		position: relative;
		display: inline-block;
		padding: 20px;
		width: 50%;
		font-size: 14px;
		line-height: 20px;
		letter-spacing: 1px;
		margin: 0 auto;
		margin-left: 5px;
		margin-right: 5px;
		margin-bottom: 10px;
		text-align: center;
		border-radius: 4px;
		overflow: hidden;
		cursor: pointer;
		text-transform: uppercase;
		-webkit-transition: all 300ms linear;
		transition: all 300ms linear;
	}

	#selectCamera .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#selectCamera .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#selectCamera .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#selectCamera .checkbox-tools:checked+label::before,
	#selectCamera .checkbox-tools:not(:checked)+label::before {
		position: absolute;
		content: "";
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border-radius: 4px;
		background-image: linear-gradient(298deg, var(--red), var(--yellow));
		z-index: -1;
	}

	#selectCamera .checkbox-tools:checked+label .uil,
	#selectCamera .checkbox-tools:not(:checked)+label .uil {
		font-size: 24px;
		line-height: 24px;
		display: block;
		padding-bottom: 10px;
	}

	.head-switch {
		/* max-width: 1000px; */
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		/* justify-content: space-around; */
		margin-left: 10px;
	}

	.switch-holder {
		display: flex;
		border-radius: 10px;
		justify-content: space-between;
		align-items: start;
	}

	.switch-label {
		width: 150px;
		text-align: end;
	}

	.switch-toggle input[type="checkbox"] {
		position: absolute;
		opacity: 0;
		z-index: -2;
	}

	.switch-toggle input[type="checkbox"]+label {
		position: relative;
		display: inline-block;
		width: 100px;
		height: 40px;
		border-radius: 20px;
		margin: 0;
		cursor: pointer;
		box-shadow: 1px 1px 4px 1px;
	}

	.switch-toggle input[type="checkbox"]+label::before {
		position: absolute;
		content: "Scan";
		font-size: 13px;
		text-align: center;
		line-height: 25px;
		top: 8px;
		left: 8px;
		width: 45px;
		height: 25px;
		color: #fff;
		border-radius: 20px;
		background-color: #5bc0de;
		box-shadow: -3px -3px 5px rgba(255, 255, 255, 0.5), 3px 3px 5px #5bc0de;
		transition: 0.3s ease-in-out;
	}

	.switch-toggle input[type="checkbox"]:checked+label::before {
		left: 50%;
		content: "Input";
		color: #fff;
		background-color: #f0ad4e;
		box-shadow: -3px -3px 5px rgba(255, 255, 255, 0.5), 3px 3px 5px #f0ad4e;
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