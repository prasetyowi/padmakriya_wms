<style>
	/* .location-rak {
      display: flex;
      flex-wrap: wrap;
   } */

	.sub-location-rak {
		border: 1px solid #334155;
		padding-top: 10px;
		padding-bottom: 10px;
		width: 15%;
		align-self: center;
		text-align: center;
		margin: 10px;
		font-size: 15px;
		text-transform: uppercase;
		letter-spacing: 2px;
		font-weight: 600;
		border-radius: 10px;
		box-shadow: 1px 1px #334155;
		cursor: pointer;
	}

	.sub-location-rak:hover {
		background-color: #cbd5e1;
	}

	.is-filled {
		margin-left: 10px;
		display: flex;
		font-weight: 500;
	}

	.is-filled-sub {
		padding: 10px;
		display: block;
		border-radius: 5px;
		background-color: #22c55e;
		margin-left: 3px;
		margin-right: 3px;
	}

	#selectCameraPallet {
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

	#selectCameraPallet .checkbox-tools:checked+label,
	#selectCameraPallet .checkbox-tools:not(:checked)+label {
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

	#selectCameraPallet .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#selectCameraPallet .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#selectCameraPallet .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#selectCameraPallet .checkbox-tools:checked+label::before,
	#selectCameraPallet .checkbox-tools:not(:checked)+label::before {
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

	#selectCameraPallet .checkbox-tools:checked+label .uil,
	#selectCameraPallet .checkbox-tools:not(:checked)+label .uil {
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
		justify-content: space-around;
	}

	.head-switchindex {
		padding: 15px;
		margin-bottom: 20px;
		/* max-width: 1000px; */
		width: 100%;
		display: flex;
		flex-wrap: nowrap;
		justify-content: space-between;
		flex-direction: row;
		align-content: center;
		align-items: baseline;
	}

	.switch-holder {
		display: flex;
		border-radius: 10px;
		justify-content: space-between;
		align-items: center;
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

	@media (max-width: 800px) {

		#select_kamera_by_one .checkbox-tools:checked+label,
		#select_kamera_by_one .checkbox-tools:not(:checked)+label {
			flex: 100%;
		}
	}
</style>