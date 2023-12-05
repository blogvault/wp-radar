<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />';
$plugin_logo = plugins_url("/../img/wp_radar.png", __FILE__);
$beta_tag = plugins_url("/../img/beta.png", __FILE__);
$running_logo = plugins_url("/../img/running.gif", __FILE__);
$done_logo = plugins_url("/../img/done.png", __FILE__);
$fail_logo = plugins_url("/../img/fail.png", __FILE__);
$error_logo = plugins_url("/../img/error.png", __FILE__);
?>
<div class="wrap">
	<div class="text-center" style="padding: 5% 0 2%"> <!-- Center the logo vertically -->
		<div style="position: relative; display: inline-block;">
			<img height="100" src="<?php echo esc_url($plugin_logo); ?>" alt="Logo">
			<img height="35" src="<?php echo esc_url($beta_tag); ?>" alt="Beta Tag" style="position: absolute; bottom: 0; right: 0;">
		</div>
	</div>
	<h2 style="text-align: center;">WP-Radar - WordPress Security Testing Plugin</h2>
	<div style="text-align: center; padding-top: 17px;">
		<div id="url-box" style="border: 2px solid #333; background-color: #f0f0f0; padding: 10px; display: inline-block;">
			<span style="font-weight: bold;">Target URL:</span>
			<span id="target-url" style="font-size: 16px;"></span>
		</div>
	</div>
	<div id="table-container" style="display: flex; justify-content: center; align-items: center; padding-top: 17px;" >
	</div>
</div>

<script>
var bv_vulnerability_list = <?php echo json_encode(VulnerabilitySimulator::vulnerabilityList()); ?>;
var bv_test_site_url = "<?php echo $_REQUEST['target_url']?>";
var bv_test_site_url_b64 = btoa(bv_test_site_url);
var bv_test_site_key = "<?php echo $_REQUEST['site_key']?>";
var bv_source_site_url = "<?php echo get_site_url(); ?>";
const trackCount = {
	running: bv_vulnerability_list.length,
	passed: 0,
	failed: 0,
	error: 0,
};

function getVulnDiv(id, content, example) {
	const vulnDiv = document.createElement('div');
	vulnDiv.classList.add('vulnerability'); // You can add CSS classes for styling

	const vulnTitle = document.createElement('p');
	vulnTitle.textContent = id;
	vulnTitle.classList.add('text-nowrap');
	vulnTitle.classList.add('vuln-id');
	vulnTitle.style.fontSize = '1rem';
	vulnTitle.style.fontWeight = 'bold';
	vulnDiv.appendChild(vulnTitle);

	const vulnContent = document.createElement('p');
	vulnContent.classList.add('description');
	vulnContent.textContent = content;
	vulnContent.style.fontSize = '0.9rem';
	vulnDiv.appendChild(vulnContent);

	const vulnExample = document.createElement('p');
	vulnExample.classList.add('example');

	const strongElement = document.createElement('strong');
	strongElement.textContent = "Found In:";
	vulnExample.appendChild(strongElement);
	vulnExample.appendChild(document.createTextNode(" "));
	vulnExample.appendChild(document.createTextNode(example));

	vulnDiv.appendChild(vulnExample);

	return vulnDiv;
}

function getStatusDiv(logoUrl, statusText) {
	const statusDiv = document.createElement('div');
	statusDiv.classList.add('status'); // You can add CSS classes for styling
	statusDiv.style.display = 'flex'; // Enable flexbox layout
	statusDiv.style.flexDirection = 'column'; // Stack child elements vertically
	statusDiv.style.alignItems = 'center'; // Center items horizontally
	statusDiv.style.justifyContent = 'center'; // Align items to the bottom

	const logoImg = document.createElement('img');
	logoImg.classList.add('logo');
	logoImg.src = logoUrl;
	statusDiv.appendChild(logoImg);

	const statusTextElement = document.createElement('strong');
	statusTextElement.classList.add('text-nowrap');
	statusTextElement.style.fontSize = '14px';
	statusTextElement.textContent = statusText;
	statusDiv.appendChild(statusTextElement);

	return statusDiv;
}

function createCard(id, name, content, example) {
	const card = document.createElement('div');
	card.classList.add('card'); // You can add CSS classes for styling
	card.classList.add('vuln-rec')

	const rowDiv = document.createElement('div');
	rowDiv.classList.add('d-flex');
	rowDiv.style.gap = "15px";
	rowDiv.style.justifyContent = "space-between";

	const vulnDiv = getVulnDiv(name, content, example);
	rowDiv.appendChild(vulnDiv);

	const statusDiv = getStatusDiv("<?php echo esc_url($running_logo); ?>", "Running");
	statusDiv.setAttribute('data-vuln-id', id);
	rowDiv.appendChild(statusDiv);
	card.appendChild(rowDiv);

	return card;
}

function createIcon(name){
	if (name == "bi-hourglass-split") {
		msg = "Running Tests";
	} else if(name == "bi-check-circle-fill"){
		msg = "Passed Tests"
	} else if(name == "bi-x-circle-fill"){
		msg = "Failed Tests"
	} else if(name == "bi-exclamation-triangle-fill"){
		msg = "Error While executing Tests";
	} else {
		msg = "undefined";
	}
	let icon = document.createElement('i');
	icon.classList.add('bi');
	icon.classList.add('desc-icon')
	icon.classList.add(name);
	icon.setAttribute('data-bs-toggle',"tooltip");
	icon.setAttribute('data-bs-placement', 'left');
	icon.setAttribute('title', msg);
	icon.set
	return icon;
}

function changeIconElement(id, count, iconName){
	let element = document.getElementById(id);
	element.textContent = count;
	element.appendChild(createIcon(iconName));
}

function changeVisibility(id, count){
	element = document.getElementById(id);
	if(count <= 0) {
		element.style.display = 'none';
	}
	else{
		element.style.display = 'flex';
	}
}

function getCardDescriptionElement() {
	let cardDescriptionElement = document.createElement('div');
	let leftElement = document.createElement('div');
	let rightElement = document.createElement('div');
	let testsElement = document.createElement('span');
	let runningElement = document.createElement('span');
	let passedElement = document.createElement('span');
	let failedElement = document.createElement('span');
	let errorElement = document.createElement('span');

	cardDescriptionElement.classList.add('card-description');
	cardDescriptionElement.classList.add('d-flex');
	cardDescriptionElement.classList.add('justify-content-between');
	leftElement.classList.add('left-description');
	leftElement.classList.add('d-flex');
	leftElement.classList.add('justify-content-between');
	rightElement.classList.add('right-description');
	rightElement.classList.add('d-flex');
	testsElement.setAttribute('id', 'test-summary');
	runningElement.setAttribute('id', 'running-summary');
	passedElement.setAttribute('id', 'passed-summary');
	failedElement.setAttribute('id', 'failed-summary');
	errorElement.setAttribute('id', 'error-summary');
	runningElement.classList.add('result-summary');
	passedElement.classList.add('result-summary');
	failedElement.classList.add('result-summary');
	errorElement.classList.add('result-summary');
	testsCountElement = document.createElement('span');
	testsCountElement.setAttribute('id', 'desc-total-test-count')
	testsCountElement.textContent = bv_vulnerability_list.length

	testsElement.textContent = "Tests";
	runningElement.textContent = trackCount.running;
	runningElement.appendChild(createIcon("bi-hourglass-split"));
	passedElement.textContent = trackCount.passed;
	passedElement.appendChild(createIcon("bi-check-circle-fill"));
	failedElement.textContent = trackCount.failed;
	failedElement.appendChild(createIcon("bi-x-circle-fill"));
	errorElement.textContent = trackCount.error;
	errorElement.appendChild(createIcon("bi-exclamation-triangle-fill"));
	passedElement.style.display = 'none';
	failedElement.style.display = 'none';
	errorElement.style.display = 'none';

	leftElement.append(testsCountElement, testsElement);
	rightElement.append(runningElement, passedElement, failedElement, errorElement);
	cardDescriptionElement.append(leftElement, rightElement);
	return cardDescriptionElement;
}

function getMainElement() {
	let mainElement = document.createElement('div');
	mainElement.classList.add('card-container'); // You can add CSS classes for styling

	return mainElement;
}

function createPage() {
	var cardDescriptionElement = getCardDescriptionElement();
	var mainElement = getMainElement();

	for (var i = 0; i < bv_vulnerability_list.length; i++) {
		let vulnerability_rec = bv_vulnerability_list[i];
		var card = createCard(vulnerability_rec["id"], vulnerability_rec["name"], vulnerability_rec["description"], vulnerability_rec["example"]);
		mainElement.appendChild(card);
	}
	document.getElementById('table-container').appendChild(cardDescriptionElement);
	document.getElementById('table-container').appendChild(mainElement);

	document.getElementById("target-url").textContent = bv_test_site_url;
}

function handlePromises(promises, results) {
	Promise.all(promises)
		.then((res) => {
		console.log("Promise Resolved")
			promises = []
			Object.keys(results).forEach(function (key) {
				let value = results[key]
					let node_element = document.querySelector('[data-vuln-id="' + key + '"]')
					if (node_element) {
						if (value == "Blocked") {
							node_element.querySelector('img').src = "<?php echo esc_url($done_logo); ?>"
						} else if (value == "Exploited") {
							node_element.querySelector('img').src = "<?php echo esc_url($fail_logo); ?>"
						} else {
							node_element.querySelector('img').src = "<?php echo esc_url($error_logo); ?>"
						}
						node_element.querySelector('strong').innerHTML = value;
					} else {
						console.log('Node element not found: ' + key)
					}
			})
}).catch(error => {
console.log(error)
	promises = []
					})
}

function testVulnerability() {
	let node_elements = document.querySelectorAll('[data-vuln-id]')
		let promises = []
		let results = {}
		for(var i = 0; i < node_elements.length; i++) {
			let node_element = node_elements[i]
				let vuln_id = node_element.getAttribute('data-vuln-id')
				promises.push(makeHttpRequest(vuln_id, results))
				if (i > 0 && i % 4 == 0) {
					handlePromises(promises, results)
						results = {}
				}
		}

	if (promises.length > 0) {
		handlePromises(promises, results)
	}
}

function makeHttpRequest(vuln_id, results) {
	var url = bv_source_site_url + '?bvplugname=wp_radar&vuln_id=' + vuln_id + '&target_url=' + bv_test_site_url_b64 + '&site_key=' + bv_test_site_key;
	
	return fetch(url)
		.then(response => {
		if (response.status == 200) {
			results[vuln_id] = 'Exploited'
			trackCount.failed += 1;
		} else if(response.status == 403) {
			results[vuln_id] = 'Blocked'
			trackCount.passed += 1;
		} else if(response.status == 404) {
			results[vuln_id] = 'Incorrect Key'
			trackCount.error += 1;
		} else if(response.status == 422) {
	       		results[vuln_id] = 'Skipped'
			trackCount.error += 1;
		} else {
			results[vuln_id] = 'Unknown Response: ' + response.status
			trackCount.error += 1;
		}
		trackCount.running -= 1;
		changeIconElement('running-summary', trackCount.running, "bi-hourglass-split");
		changeIconElement('passed-summary', trackCount.passed, "bi-check-circle-fill");
		changeIconElement('failed-summary', trackCount.failed, "bi-x-circle-fill");
		changeIconElement('error-summary', trackCount.error, "bi-exclamation-triangle-fill");
		changeVisibility('failed-summary', trackCount.failed);
		changeVisibility('passed-summary', trackCount.passed);
		changeVisibility('error-summary', trackCount.error);
		changeVisibility('running-summary', trackCount.running);
		let resp_text = response.text()
			console.log(resp_text)
			return resp_text;
	})
		.catch(error => {
		console.error('Error:', error);
		results[vuln_id] = 'Failed';
		return 'Failed';
			});
}

createPage();
testVulnerability();
</script>
