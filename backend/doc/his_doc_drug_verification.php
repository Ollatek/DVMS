<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drug Verification</title>
    <link rel="stylesheet" href="assets/css/verify_style.css"> 
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease-in-out;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            width: 50%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
            position: relative;
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Close Button */
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            font-weight: bold;
            color: #555;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .close:hover {
            color: red;
        }
    </style>
</head>
<body>

    <h2>Drug Verification</h2>
    <form id="verificationForm">
        <label for="search">Enter Drug Name / RFID Tag / Barcode:</label>
        <input type="text" id="search" name="search" required placeholder="Enter drug name, scan RFID tag, or enter barcode">
        <button type="submit">Verify</button>
        <button type="button" id="startScanBtn">Scan QR Code</button>
        <span id="scanIndicator" style="display: none; color: green; font-weight: bold;">Scanning...</span>
    </form>

    <div id="qr-reader" style="width:300px; display: none;"></div>

    <!-- Modal -->
    <div id="resultModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Drug Verification Results</h3>
            <div id="modalContent"></div> 
        </div>
    </div>

    <script>
        document.getElementById('verificationForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let searchQuery = document.getElementById('search').value.trim();
            if (!searchQuery) {
                alert("Please enter a valid drug name or code.");
                return;
            }

            fetchDrugData(searchQuery);
        });

        function fetchDrugData(searchQuery) {
            let modal = document.getElementById('resultModal');
            let modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = "<p>Loading...</p>";
            modal.style.display = 'flex';

            let localApiUrl = `search_drug.php?search=${encodeURIComponent(searchQuery)}`;
            let externalApis = [
                `https://api.fda.gov/drug/label.json?search=openfda.brand_name:${encodeURIComponent(searchQuery)}`,
                `https://rxnav.nlm.nih.gov/REST/drugs.json?name=${encodeURIComponent(searchQuery)}`
            ];

            let fetchRequests = [fetch(localApiUrl).then(response => response.json())];
            externalApis.forEach(api => fetchRequests.push(fetch(api).then(response => response.json()).catch(() => null)));

            Promise.all(fetchRequests)
            .then(([localData, fdaData, rxNavData]) => {
                modalContent.innerHTML = '';
                
                if (localData.length > 0) {
                    let drug = localData[0];
                    modalContent.innerHTML += `
                        <p><strong>Drug Name:</strong> ${drug.name || 'Not Available'}</p>
                        <p><strong>NAFDAC No:</strong> ${drug.nafdac_reg_no || 'Unknown'}</p>
                        <p><strong>Manufacturer:</strong> ${drug.manufacturer || 'Not Available'}</p>
                        <p><strong>Manufacturing Date:</strong> ${drug.manufacture_date || 'Not Available'}</p>
                        <p><strong>Expiry Date:</strong> ${drug.expiry_date || 'Not Available'}</p>
                        <hr>
                    `;
                }
                
                if (fdaData && fdaData.results) {
                    let externalDrug = fdaData.results[0];
                    modalContent.innerHTML += `
                        <p><strong>Brand Name:</strong> ${externalDrug.openfda.brand_name || 'Not Available'}</p>
                        <p><strong>Active Ingredients:</strong> ${externalDrug.active_ingredient || 'Not Available'}</p>
                        <p><strong>Warnings:</strong> ${externalDrug.warnings || 'No warnings found'}</p>
                    `;
                }
                
                if (rxNavData && rxNavData.drugGroup && rxNavData.drugGroup.conceptGroup) {
                    let rxInfo = rxNavData.drugGroup.conceptGroup;
                    modalContent.innerHTML += `<p><strong>RxNorm Information:</strong></p>`;
                    rxInfo.forEach(group => {
                        if (group.conceptProperties) {
                            group.conceptProperties.forEach(concept => {
                                modalContent.innerHTML += `<p>${concept.name}</p>`;
                            });
                        }
                    });
                }
                
                if (!localData.length && (!fdaData || !fdaData.results) && (!rxNavData || !rxNavData.drugGroup)) {
                    modalContent.innerHTML += "<p>No matching drug data found.</p>";
                }
            })
            .catch(error => {
                console.error("Error fetching drug data:", error);
                modalContent.innerHTML = `<p style="color: red;">Failed to fetch data.</p>`;
            });
        }

        function closeModal() {
            document.getElementById('resultModal').style.display = 'none';
        }

        function onScanSuccess(decodedText) {
            document.getElementById('search').value = decodedText;
            fetchDrugData(decodedText);
            stopScanning();
        }

        let html5QrCode = new Html5Qrcode("qr-reader");

        document.getElementById('startScanBtn').addEventListener('click', function() {
            document.getElementById('qr-reader').style.display = 'block';
            document.getElementById('scanIndicator').style.display = 'inline'; // Show "Scanning..."

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess
            ).catch(err => console.error("QR Code Scanner Error:", err));
        });

        function stopScanning() {
            html5QrCode.stop().then(() => {
                document.getElementById('qr-reader').style.display = 'none';
                document.getElementById('scanIndicator').style.display = 'none'; // Hide "Scanning..."
            }).catch(err => {
                console.error("Failed to stop scanning: ", err);
            });
        }
    </script>
</body>
</html>
