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

	body {
		padding-right: 0px !important;
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

	/* Hide scrollbar for Chrome, Safari and Opera */
	.hide-scrollbar::-webkit-scrollbar {
		display: none;
	}

	/* Hide scrollbar for IE, Edge and Firefox */
	.hide-scrollbar {
		-ms-overflow-style: none;
		/* IE and Edge */
		scrollbar-width: none;
		/* Firefox */
	}

	#parrent-opname {
		overflow: hidden;

	}

	.parrent-proses-opname {
		margin: 0 auto;
		height: auto;
		min-height: 108vh;
		overflow-y: scroll;
	}

	.child-proses-opname {
		padding: 7px;
	}

	section {
		margin-top: 20px;
		margin-bottom: 20px;
	}

	#list-table-section-perintah-kerja .parrent-table-surat-kerja {
		width: 94%;
		margin: 10px;
	}


	#list-data-section-perintah-kerja,
	#list-detail-section-perintah-kerja {
		height: calc(40vh - 20px);
	}


	.card-opname {
		padding: 20px;
		border: 1px solid black;
		width: 100%;
		border-radius: 20px 20px;
		margin-top: 5px;
		margin-bottom: 5px;
		cursor: pointer;
	}

	.card-opname-parent {
		display: flex;
		justify-content: space-between;
		align-items: center;
		align-self: center;
	}

	.card-detail-opname {
		padding: 20px;
		border: 1px solid black;
		width: 100%;
		border-radius: 20px 20px;
		margin-top: 5px;
		margin-bottom: 5px;
	}

	.card-list-opname-detail {
		margin-top: 15px;
		margin-bottom: 15px;
		border: 1px solid grey;
		border-radius: 10px 10px;
		padding: 10px;
		height: 30vh;
		overflow: hidden
	}

	.parent-data-proses {
		overflow-y: scroll;
		padding: 2px;
		height: 20vh;
	}

	.card-list-opname-detail .parent-data-proses::-webkit-scrollbar-track {
		-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
		box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
		border-radius: 10px;
		background-color: #d1d5db;
	}

	.card-list-opname-detail .parent-data-proses::-webkit-scrollbar {
		width: 7px;
		background-color: #d1d5db;
	}

	.card-list-opname-detail .parent-data-proses::-webkit-scrollbar-thumb {
		border-radius: 10px;
		-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
		box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
		background-color: #555;
	}

	.title-proses-data {
		color: #0f172a;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 2px;
		font-family: 'Quicksand'
	}

	.child-parent-data-proses {
		display: flex;
		justify-content: space-between;
		margin-left: 20px;
		margin-right: 10px;
	}

	/* #body-modal-scan {
    height: 500px !important;
    overflow-x: hidden !important;
    overflow-y: hidden !important;
    overflow: hidden;
  } */

	.wrapper {
		display: inline-flex;
		background: transparent;
		height: 100px;
		width: 100%;
		align-items: center;
		justify-content: space-evenly;
		border-radius: 5px;
		padding: 20px 15px;
		box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.2);
	}

	.wrapper .option {
		background: #fff;
		height: 100%;
		width: 100%;
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

	input[name="selectSplit"] {
		display: none;
	}

	#option-1:checked:checked~.option-1,
	#option-2:checked:checked~.option-2 {
		border-color: #0069d9;
		background: #0069d9;
	}

	#option-1:checked:checked~.option-1 .dot,
	#option-2:checked:checked~.option-2 .dot {
		background: #fff;
	}

	#option-1:checked:checked~.option-1 .dot::before,
	#option-2:checked:checked~.option-2 .dot::before {
		opacity: 1;
		transform: scale(1);
	}

	.wrapper .option span {
		font-size: 20px;
		color: #808080;
	}

	#option-1:checked:checked~.option-1 span,
	#option-2:checked:checked~.option-2 span {
		color: #fff;
	}


	#select_kamera {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	#select_kamera .checkbox-tools {
		display: none;
	}

	#select_kamera .checkbox-tools:checked+label {
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

	#select_kamera .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#select_kamera .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera .checkbox-tools:checked+label::before {
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

	#select_kamera .checkbox-tools:checked+label .uil {
		font-size: 24px;
		line-height: 24px;
		display: block;
		padding-bottom: 10px;
	}

	.check-item {
		transform: scale(1.5);
	}


	.progress-cuy {
		border: 1px solid #cbd5e1;
		background-color: #f5f5f5;
		border-radius: 5px 5px;
		box-shadow: none;
		height: 20px;
	}

	.progress-bar-cuy {
		height: 19px;
		line-height: 19px;
		color: #0f172a;
		font-weight: 600;
		border-radius: 5px 5px;
		background-color: #2196f3;
		box-shadow: none;
	}

	.progress-cuy.active .progress-bar-cuy,
	.progress-bar-cuy.active {
		-webkit-animation: progress-bar-stripes 2s linear infinite;
		-o-animation: progress-bar-stripes 2s linear infinite;
		animation: progress-bar-stripes 2s linear infinite;
	}

	.progress-striped-cuy .progress-bar-cuy,
	.progress-bar-striped-cuy {
		background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
		background-size: 40px 40px;
	}

	@-webkit-keyframes progress-bar-stripes {
		from {
			background-position: 40px 0;
		}

		to {
			background-position: 0 0;
		}
	}

	@keyframes progress-bar-stripes {
		from {
			background-position: 40px 0;
		}

		to {
			background-position: 0 0;
		}
	}

	.progress-bar-success {
		background-color: #22c55e;
	}

	.progress-bar-warning {
		background-color: #eab308;
	}

	.progress-bar-danger {
		background-color: #dc2626;
	}

	/* Extra small devices (phones, 600px and down) */
	@media only screen and (max-width: 600px) {
		.parrent-proses-opname {
			width: 380px;
		}

		#list-table-section-perintah-kerja {
			overflow: hidden;
			border: 1px solid gray;
			border-radius: 10px 10px;
		}

		.child-parent-data-proses {
			margin-right: 10px;
		}

		.wrapper .option .dot {
			height: 18px;
			width: 18px;
		}

		.wrapper .option span {
			font-size: 13px;
		}

		#select_kamera .checkbox-tools:checked+label {
			flex: 100%;
		}

		.check-item {
			transform: scale(0.5);
		}
	}

	/* Small devices (portrait tablets and large phones, 600px and up) */
	@media only screen and (min-width: 600px) {
		.parrent-proses-opname {
			width: 80%;
		}

		.child-parent-data-proses {
			margin-right: 10px;
		}

		.wrapper .option .dot {
			height: 20px;
			width: 20px;
		}

		.wrapper .option span {
			font-size: 14px;
		}

		#select_kamera .checkbox-tools:checked+label {
			flex: 100%;
		}

		.check-item {
			transform: scale(0.8);
		}
	}

	/* Medium devices (landscape tablets, 768px and up) */
	@media only screen and (min-width: 768px) {
		.parrent-proses-opname {
			width: 80%;
		}

		.child-parent-data-proses {
			margin-right: 10px;
		}

		.wrapper .option .dot {
			height: 20px;
			width: 20px;
		}

		.wrapper .option span {
			font-size: 16px;
		}

		#select_kamera .checkbox-tools:checked+label {
			flex: 100%;
		}

		.check-item {
			transform: scale(1);
		}
	}

	/* Large devices (laptops/desktops, 992px and up) */
	@media only screen and (min-width: 992px) {
		.parrent-proses-opname {
			width: 80%;
		}

		.child-parent-data-proses {
			margin-right: 10px;
		}

		#select_kamera .checkbox-tools:checked+label {
			flex: 100%;
		}
	}
</style>
