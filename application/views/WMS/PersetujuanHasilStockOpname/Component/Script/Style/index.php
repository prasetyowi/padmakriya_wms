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

	.select2-container--default .select2-search--inline .select2-search__field {
		width: 329px !important;
	}

	/** Styling Tabs pane */

	.wrapper {
		display: inline-flex;
		margin-top: 10px;
		height: 40px;
		width: 30%;
		align-items: center;
		/* justify-content: space-evenly; */
		border-radius: 5px;
	}

	.wrapper .option {
		background: #fff;
		height: 100%;
		width: 100%;
		min-width: fit-content;
		display: flex;
		align-items: center;
		justify-content: space-evenly;
		margin: 0 10px;
		border-radius: 5px;
		cursor: pointer;
		padding: 0 10px;
		border: 2px solid lightgrey;
		transition: all 0.3s ease;
	}

	.wrapper .option .dot {
		height: 20px;
		width: 20px;
		background: #d9d9d9;
		border-radius: 50%;
		position: relative;
	}

	.wrapper .option .dot::before {
		position: absolute;
		content: "";
		top: 4px;
		left: 4px;
		width: 12px;
		height: 12px;
		background: #0069d9;
		border-radius: 50%;
		opacity: 0;
		transform: scale(1.5);
		transition: all 0.3s ease;
	}

	.chkCompareOrApproval {
		display: none;
	}

	#option-1:checked:checked~.option-1,
	#option-2:checked:checked~.option-2,
	#option-3:checked:checked~.option-3 {
		border-color: #0069d9;
		background: #0069d9;
	}

	#option-1:checked:checked~.option-1 .dot,
	#option-2:checked:checked~.option-2 .dot,
	#option-3:checked:checked~.option-3 .dot {
		background: #fff;
	}

	#option-1:checked:checked~.option-1 .dot::before,
	#option-2:checked:checked~.option-2 .dot::before,
	#option-3:checked:checked~.option-3 .dot::before {
		opacity: 1;
		transform: scale(1);
	}

	.wrapper .option span {
		font-size: 16px;
		color: #808080;
	}

	#option-1:checked:checked~.option-1 span,
	#option-2:checked:checked~.option-2 span,
	#option-3:checked:checked~.option-3 span {
		color: #fff;
	}

	/** End Styling Tabs pane */

	.compare-list {
		border: 1px solid gray;
		padding: 10px;
		border-radius: 10px 10px;
		margin: 10px 0px 10px 0px;
	}

	.compare-title-kode {
		display: inline;
		font-weight: 700;
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