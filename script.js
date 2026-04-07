
let currentUser = null;
let campaigns = [];
let phishingAttempts = [];


function simulateAESEncryption(data) {

  return btoa(data).split('').reverse().join('');
}

function simulateAESDecryption(encryptedData) {
  return atob(encryptedData.split('').reverse().join(''));
}


function showPage(pageId) {
  document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
  document.querySelectorAll('.nav-bar button').forEach(btn => btn.classList.remove('active'));
  
  document.getElementById(pageId + 'Page').classList.add('active');
  document.getElementById(pageId + 'Btn').classList.add('active');
}


function handleLogin(event) {
  event.preventDefault();
  
  const email = document.getElementById('loginEmail').value;
  const password = document.getElementById('loginPassword').value;
  const role = document.getElementById('userRole').value;
  const resultDiv = document.getElementById('loginResult');
  

  if ((email === 'admin@simulator.com' && password === 'admin123' && role === 'admin') ||
      (email === 'user@simulator.com' && password === 'user123' && role === 'user')) {
    
    currentUser = { email, role };
    resultDiv.innerHTML = `<span class="success">✅ Login successful! Welcome ${role}</span>`;
    

    document.getElementById('loginBtn').style.display = 'none';
    document.getElementById('logoutBtn').style.display = 'inline-block';
    document.getElementById('dashboardBtn').style.display = 'inline-block';
    document.getElementById('phishingBtn').style.display = 'inline-block';
    
    if (role === 'admin') {
      document.getElementById('adminBtn').style.display = 'inline-block';
      showPage('admin');
    } else {
      showPage('dashboard');
    }
    
    updateEmailPreview();
  } else {
    resultDiv.innerHTML = `<span class="error">❌ Invalid credentials or role</span>`;
  }
}

function logout() {
  currentUser = null;
  document.getElementById('loginBtn').style.display = 'inline-block';
  document.getElementById('adminBtn').style.display = 'none';
  document.getElementById('dashboardBtn').style.display = 'none';
  document.getElementById('phishingBtn').style.display = 'none';
  document.getElementById('logoutBtn').style.display = 'none';
  

  document.getElementById('loginEmail').value = '';
  document.getElementById('loginPassword').value = '';
  document.getElementById('userRole').value = '';
  document.getElementById('loginResult').innerHTML = '';
  
  showPage('login');
}


function createCampaign(event) {
  event.preventDefault();
  
  const campaign = {
    id: Date.now(),
    name: document.getElementById('campaignName').value,
    template: document.getElementById('emailTemplate').value,
    subject: document.getElementById('emailSubject').value,
    targets: document.getElementById('targetEmails').value.split('\n').filter(email => email.trim()),
    duration: document.getElementById('campaignDuration').value,
    created: new Date().toISOString(),
    status: 'active'
  };
  
  campaigns.push(campaign);
  
  document.getElementById('campaignStatus').innerHTML = 
    `<span class="success">✅ Campaign "${campaign.name}" launched successfully! Targeting ${campaign.targets.length} users.</span>`;
  

  event.target.reset();
  updateEmailPreview();
  

  simulateEmailDelivery(campaign);
}

function simulateEmailDelivery(campaign) {
  console.log(`📧 Simulating email delivery for campaign: ${campaign.name}`);
  campaign.targets.forEach(email => {
    console.log(`✉️ Email sent to: ${email}`);
  });
}


function updateEmailPreview() {
  const template = document.getElementById('emailTemplate')?.value || 'bank';
  const previewDiv = document.getElementById('previewContent');
  
  if (!previewDiv) return;
  
  const templates = {
    bank: `
      <strong>From:</strong> security@securebank.com<br>
      <strong>Subject:</strong> Urgent Account Verification Required<br><br>
      <div style="border-left: 3px solid #dc3545; padding-left: 10px;">
        Dear Valued Customer,<br><br>
        We have detected suspicious activity on your account. Please verify your credentials immediately by clicking the link below:<br><br>
        <a href="#" style="color: #007bff;">🔗 Verify Account Now</a><br><br>
        Failure to verify within 24 hours may result in account suspension.
      </div>
    `,
    it: `
      <strong>From:</strong> it-support@company.com<br>
      <strong>Subject:</strong> Mandatory System Update<br><br>
      <div style="border-left: 3px solid #28a745; padding-left: 10px;">
        Dear Employee,<br><br>
        A critical security update is required for all company systems. Please click the link below to update your credentials:<br><br>
        <a href="#" style="color: #007bff;">🔗 Update System Access</a><br><br>
        This update must be completed by end of business today.
      </div>
    `,
    hr: `
      <strong>From:</strong> hr@company.com<br>
      <strong>Subject:</strong> Updated Employee Handbook<br><br>
      <div style="border-left: 3px solid #17a2b8; padding-left: 10px;">
        Dear Team Member,<br><br>
        The employee handbook has been updated. Please review and acknowledge the changes:<br><br>
        <a href="#" style="color: #007bff;">🔗 Review Handbook</a><br><br>
        Your acknowledgment is required within 48 hours.
      </div>
    `,
    social: `
      <strong>From:</strong> notifications@socialnetwork.com<br>
      <strong>Subject:</strong> Suspicious Login Detected<br><br>
      <div style="border-left: 3px solid #6f42c1; padding-left: 10px;">
        Hi there,<br><br>
        We noticed a suspicious login to your account from an unknown device. If this wasn't you, please secure your account:<br><br>
        <a href="#" style="color: #007bff;">🔗 Secure Account</a><br><br>
        Stay safe online!
      </div>
    `
  };
  
  previewDiv.innerHTML = templates[template];
}


function handleFileUpload(event) {
  const file = event.target.files[0];
  const statusDiv = document.getElementById('fileStatus');
  
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const fileContent = e.target.result;
      

      const encryptedContent = simulateAESEncryption(fileContent);
      
      statusDiv.innerHTML = `
        <div class="success">
          ✅ File "${file.name}" uploaded and encrypted successfully!<br>
          📁 Size: ${(file.size / 1024).toFixed(2)} KB<br>
          🔐 Encryption: AES-256 (simulated)<br>
          📅 Date: ${new Date().toLocaleString()}
        </div>
      `;
      
      console.log('Original content:', fileContent.substring(0, 100) + '...');
      console.log('Encrypted content:', encryptedContent.substring(0, 100) + '...');
    };
    reader.readAsText(file);
  }
}


function handlePhishingAttempt(event) {
  event.preventDefault();
  
  const account = document.getElementById('phishingAccount').value;
  const username = document.getElementById('phishingUsername').value;
  const password = document.getElementById('phishingPassword').value;
  const pin = document.getElementById('phishingPin').value;
  

  const attempt = {
    timestamp: new Date().toISOString(),
    account: account,
    username: username,
    password: password,
    pin: pin,
    userAgent: navigator.userAgent,
    ip: '192.168.1.100'
  };
  
  phishingAttempts.push(attempt);
  
  console.log('🎣 Phishing attempt captured:', attempt);
  

  document.getElementById('phishingResult').innerHTML = `
    <div class="error">
      🚨 <strong>You've been phished!</strong><br><br>
      This was a simulation to test your security awareness. In a real attack, your credentials would now be compromised.<br><br>
      <strong>🛡️ Security Tips:</strong><br>
      • Always verify the sender's email address<br>
      • Check the website URL carefully<br>
      • Never enter sensitive information via email links<br>
      • When in doubt, contact your IT department<br><br>
      <em>This data has been logged for security training purposes.</em>
    </div>
  `;
  

  updateDashboardStats();
}

function updateDashboardStats() {
  if (document.getElementById('phishedUsers')) {
    document.getElementById('phishedUsers').textContent = phishingAttempts.length;
  }
}


function exportResults() {
  const results = {
    campaigns: campaigns,
    phishingAttempts: phishingAttempts,
    exportDate: new Date().toISOString(),
    generatedBy: currentUser?.email || 'system'
  };
  
  const dataStr = JSON.stringify(results, null, 2);
  const dataBlob = new Blob([dataStr], {type: 'application/json'});
  const url = URL.createObjectURL(dataBlob);
  
  const link = document.createElement('a');
  link.href = url;
  link.download = `phishing_simulation_results_${new Date().toISOString().split('T')[0]}.json`;
  link.click();
  
  URL.revokeObjectURL(url);
}


class PhishingSimulatorContract {
  constructor() {
    this.campaigns = new Map();
    this.users = new Map();
    this.results = new Map();
  }
  

  deployCampaign(campaignData) {
    const campaignId = this.generateId();
    this.campaigns.set(campaignId, {
      ...campaignData,
      id: campaignId,
      deployed: true,
      timestamp: Date.now()
    });
    return campaignId;
  }
  

  recordAttempt(userId, campaignId, attemptData) {
    const resultId = this.generateId();
    this.results.set(resultId, {
      userId,
      campaignId,
      ...attemptData,
      timestamp: Date.now(),
      verified: true
    });
    return resultId;
  }
  

  getCampaignStats(campaignId) {
    const campaign = this.campaigns.get(campaignId);
    if (!campaign) return null;
    
    const attempts = Array.from(this.results.values())
      .filter(result => result.campaignId === campaignId);
    
    return {
      totalAttempts: attempts.length,
      successfulPhishing: attempts.filter(a => a.phished).length,
      successRate: attempts.length > 0 ? 
        (attempts.filter(a => a.phished).length / attempts.length * 100).toFixed(2) + '%' : '0%'
    };
  }
  
  generateId() {
    return 'contract_' + Math.random().toString(36).substr(2, 9);
  }
}


const simulatorContract = new PhishingSimulatorContract();


document.addEventListener('DOMContentLoaded', function() {
  console.log('🚀 Phishing Email Simulator initialized');
  console.log('📊 Features: Login/Auth ✅, File Upload ✅, Encryption ✅, Smart Contract Draft ✅');
  console.log('🔗 Smart Contract deployed:', simulatorContract);
  

  updateEmailPreview();
  

  const initialCampaign = simulatorContract.deployCampaign({
    name: 'Demo Campaign',
    template: 'bank',
    targets: ['demo@example.com']
  });
  
  console.log('📋 Demo campaign deployed with ID:', initialCampaign);
});