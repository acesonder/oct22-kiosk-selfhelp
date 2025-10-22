import { Link } from 'react-router-dom';
import './LandingPage.css';

function LandingPage() {
  return (
    <div className="landing-page">
      <header className="hero">
        <h1>Kiosk Self-Help Platform</h1>
        <p className="tagline">
          Connecting those in need with resources for homeless, addiction, mental health, and other support services
        </p>
      </header>

      <section className="features">
        <h2>Platform Features</h2>
        <div className="feature-grid">
          <div className="feature-card">
            <div className="feature-icon">🏠</div>
            <h3>Housing Support</h3>
            <p>Find emergency shelters, transitional housing, and permanent housing solutions</p>
          </div>
          <div className="feature-card">
            <div className="feature-icon">🏥</div>
            <h3>Health Services</h3>
            <p>Access mental health support, addiction recovery programs, and medical care</p>
          </div>
          <div className="feature-card">
            <div className="feature-icon">🍽️</div>
            <h3>Food & Basic Needs</h3>
            <p>Locate food banks, meal programs, and assistance with essential items</p>
          </div>
          <div className="feature-card">
            <div className="feature-icon">💼</div>
            <h3>Employment Services</h3>
            <p>Job training, placement assistance, and career counseling resources</p>
          </div>
          <div className="feature-card">
            <div className="feature-icon">📚</div>
            <h3>Education & Training</h3>
            <p>Educational programs, skill development, and certification opportunities</p>
          </div>
          <div className="feature-card">
            <div className="feature-icon">👥</div>
            <h3>Community Support</h3>
            <p>Connect with support groups, counseling, and peer assistance networks</p>
          </div>
        </div>
      </section>

      <section className="user-types">
        <h2>Access the Platform</h2>
        <div className="user-cards">
          <Link to="/client" className="user-card">
            <div className="user-icon">🤝</div>
            <h3>Client Portal</h3>
            <p>Access resources and services tailored to your needs</p>
            <button className="access-btn">Get Help Now</button>
          </Link>
          
          <Link to="/outreach" className="user-card">
            <div className="user-icon">👨‍💼</div>
            <h3>Outreach Staff</h3>
            <p>Manage clients, track cases, and coordinate services</p>
            <button className="access-btn">Staff Login</button>
          </Link>
          
          <Link to="/provider" className="user-card">
            <div className="user-icon">🏢</div>
            <h3>Service Provider</h3>
            <p>Manage your organization's services and availability</p>
            <button className="access-btn">Provider Portal</button>
          </Link>
        </div>
      </section>

      <section className="tools">
        <h2>Tools & Services</h2>
        <div className="tools-list">
          <div className="tool-item">
            <span className="tool-icon">🔍</span>
            <div>
              <h4>Resource Finder</h4>
              <p>Search and filter available services by location, type, and availability</p>
            </div>
          </div>
          <div className="tool-item">
            <span className="tool-icon">📱</span>
            <div>
              <h4>Mobile Access</h4>
              <p>Access the platform on any device, anywhere, anytime</p>
            </div>
          </div>
          <div className="tool-item">
            <span className="tool-icon">🗂️</span>
            <div>
              <h4>Case Management</h4>
              <p>Track progress, appointments, and service utilization</p>
            </div>
          </div>
          <div className="tool-item">
            <span className="tool-icon">📊</span>
            <div>
              <h4>Analytics & Reporting</h4>
              <p>Data-driven insights to improve service delivery</p>
            </div>
          </div>
          <div className="tool-item">
            <span className="tool-icon">🔒</span>
            <div>
              <h4>Secure & Private</h4>
              <p>HIPAA-compliant platform protecting client confidentiality</p>
            </div>
          </div>
          <div className="tool-item">
            <span className="tool-icon">🌐</span>
            <div>
              <h4>Multi-Language Support</h4>
              <p>Services available in multiple languages for accessibility</p>
            </div>
          </div>
        </div>
      </section>

      <footer className="landing-footer">
        <p>© 2025 Kiosk Self-Help Platform. Helping connect communities with resources.</p>
      </footer>
    </div>
  );
}

export default LandingPage;
