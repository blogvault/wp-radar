<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />';
$plugin_logo = plugins_url("/../img/wp_radar.png", __FILE__);
$beta_tag = plugins_url("/../img/beta.png", __FILE__);
?>

<div class="wrap">
	<div class="text-center" style="padding: 5% 0 2%"> <!-- Center the logo vertically -->
		<div style="position: relative; display: inline-block;">
			<img height="100" src="<?php echo esc_url($plugin_logo); ?>" alt="Logo">
			<img height="35" src="<?php echo esc_url($beta_tag); ?>" alt="Beta Tag" style="position: absolute; bottom: 0; right: 0;">
		</div>
	</div>
	<h2 style="text-align: center;">WP-Radar - WordPress Security Testing Plugin</h2>
	<?php
		$site_key = WordpressOpsHelper::getOption(WPRadarInfo::$secret_key_name);
	?>
	<div id="choose-site">
		<h4 id="header-desc"><span>Choose this site's role</span></h4>
		<div class='d-flex flex-column font-14' id="body">
			<div class="col-xs-11 panel" style="border:1px solid #D3E4F8;">
				<div class='summary' id='attacker'>
					<div class='d-flex align-items-center'>
						<i class="bi bi-circle checkbox-icon"></i>
						<span class='title'>Attacker Site</span>
					</div>
				</div>
				<form action="<?php echo esc_url($this->mainUrl('&test_started=true'));?>" method="post" id='attacker-body'>
					<div class="form-group mb-4"> <!-- Add bottom margin to the form group and make it grow -->
						<input type='url' name='target_url' required class="form-control" placeholder="Target site url"/>
					</div>
					<div class="form-group mb-4"> <!-- Add bottom margin to the form group and make it grow -->
						<div class="input-group">
							<input type='password' name='site_key' required class="form-control" placeholder="Target site key" minlength="32" maxlength="32"/>
							<i class="bi bi-eye-slash toggle-key"" onclick="onToggleClicked(this)"></i>
							<span class="input-group-text" id="paste-key">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2" viewBox="0 0 16 16">
									<path d="M3.5 2a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-12a.5.5 0 0 0-.5-.5H12a.5.5 0 0 1 0-1h.5A1.5 1.5 0 0 1 14 2.5v12a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-12A1.5 1.5 0 0 1 3.5 1H4a.5.5 0 0 1 0 1h-.5Z"></path>
									<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
								</svg>
							</span>
						</div>
						<div class="info-message">
							Get the target site key by installing the plugin on the target site.
						</div>
					</div>
					<div class="form-group text-center"> <!-- Center the button within the form group and make it occupy the entire width -->
						<button type="submit" class="btn btn-dark btn-sm">Start testing</button>
					</div>
				</form>
			</div>
			<div class="align-items-center d-flex justify-content-center" style="padding: 0 70px">
				<span class="divider"></span>
				<span class="divider-text">OR</span>
				<span class="divider"></span>
			</div>
			<div class="col-xs-11 panel" style="border:1px solid #D3E4F8;">
				<div class='summary' id='target'>
					<div class='d-flex align-items-center'>
						<i class="bi bi-circle checkbox-icon"></i>
						<span class='title'>Target Site</span>
					</div>
				</div>
				<div id='target-body'>
					<div class="d-flex align-items-center">
						<label for="siteKeyDisplay"style="min-width: fit-content;padding-right: 14px;">
							Site Key
						</label>
						<div class="input-group">
							<input type='password' readonly name='siteKeyDisplay' id='siteKeyDisplay' class="form-control" value="<?php echo esc_attr($site_key); ?>"/>
							<i class="bi bi-eye-slash toggle-key" onclick="onToggleClicked(this)"></i>
							<span class="input-group-text" id="copy-key">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6ZM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1H2Z"></path>
								</svg>
							</span>
						</div>
					</div>
					<div class="info-message">
						Key to input on attacker site's plugin.
					</div>
				</div>
			</div>
		</div>
	</div>
	<p class="mx-auto text-center text-muted" style="font-weight: 600;width: 600px;max-width: 95%;">
		WP-Radar operates using an attacker-target site configuration.
		WP-Radar evaluates site security by recreating a real threat scenario, but in a safe and controlled environment.
		The attacker site requires the target site's URL and site key to initiate attack testing, creating requests that mimic attack requests.
	</p>
</div>
<script>
const checkClass = 'bi-check-circle-fill';
const uncheckClass = 'bi-circle';
const attacker = document.getElementById('attacker');
const target = document.getElementById('target');
const attackerBody = document.getElementById('attacker-body');
const targetBody = document.getElementById('target-body');
let currentSelected = null
attacker.addEventListener('click', () => {
	if (currentSelected === attacker) {
		return;
	}
	if (currentSelected) {
		targetBody.classList.remove('expand');
		targetBody.classList.add('collapse');
	}
	attackerBody.classList.remove('collapse');
	attackerBody.classList.add('expand');
	attacker.querySelector('i').classList.remove(uncheckClass);
	attacker.querySelector('i').classList.add(checkClass);
	target.querySelector('i').classList.remove(checkClass);
	target.querySelector('i').classList.add(uncheckClass);
	currentSelected = attacker;
});
target.addEventListener('click', () => {
	if (currentSelected === target) {
		return;
	}
	if (currentSelected) {
		attackerBody.classList.remove('expand');
		attackerBody.classList.add('collapse');
	}
	targetBody.classList.remove('collapse');
	targetBody.classList.add('expand');
	target.querySelector('i').classList.remove(uncheckClass);
	target.querySelector('i').classList.add(checkClass);
	attacker.querySelector('i').classList.remove(checkClass);
	attacker.querySelector('i').classList.add(uncheckClass);
	currentSelected = target;
});
const copyButton = document.getElementById('copy-key');
copyButton.addEventListener('click', () => {
	const siteKeyDisplay = document.getElementById('siteKeyDisplay');
	siteKeyDisplay.select();
	siteKeyDisplay.setSelectionRange(0, 32);
	if (navigator.clipboard) {
		navigator.clipboard.writeText(siteKeyDisplay.value).then(() => {
			copyButton.innerHTML = 'Copied';
			setTimeout(() => {
				copyButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
								<path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6ZM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1H2Z"></path>
							</svg>`;
			}, 1000);
		});
	} else {
        console.error("Clipboard API is not supported in this browser.");
        const alternativeMessage = "Your browser does not support the Clipboard API. You may manually copy the key.";
        alert(alternativeMessage);
    }
});
const setPasteListener = async () => {
	const pasteButton = document.getElementById('paste-key');
	try {
		await navigator.clipboard.readText()
		pasteButton.addEventListener('click', () => {
			navigator.clipboard.readText().then(text => {
				const siteKeyDisplay = document.getElementsByName('site_key')[0];
				siteKeyDisplay.value = text;
				if (text.length > 0) {
					checkValidity();
				}
			});
		});
	} catch {
		pasteButton.previousElementSibling.style.borderRadius = '0 4px 4px 0';
		pasteButton.style.display = 'none';
	}
}
document.addEventListener('DOMContentLoaded', setPasteListener);
const onToggleClicked = (element) => {
	const input = element.previousElementSibling;
	if (input.type === 'password') {
		input.type = 'text';
		element.classList.remove('bi-eye-slash');
		element.classList.add('bi-eye');
	} else {
		input.type = 'password';
		element.classList.remove('bi-eye');
		element.classList.add('bi-eye-slash');
	}
}
const checkValidity = () => {
	const targetUrl = document.getElementsByName('target_url')[0];
	const siteKey = document.getElementsByName('site_key')[0];
	if (targetUrl.checkValidity() && siteKey.checkValidity()) {
		attackerBody.querySelector('button[type="submit"]').removeAttribute('disabled');
		attackerBody.querySelector('button[type="submit"]').classList.remove('disabled');
	} else {
		attackerBody.querySelector('button[type="submit"]').setAttribute('disabled', true);
		attackerBody.querySelector('button[type="submit"]').classList.add('disabled');
	}
}
document.getElementsByName('target_url')[0].addEventListener('input', checkValidity);
document.getElementsByName('site_key')[0].addEventListener('input', checkValidity);
checkValidity();
</script>
