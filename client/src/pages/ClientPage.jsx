import { Link } from 'react-router-dom';
import './ClientPage.css';

function ClientPage() {
  return (
    <div className="client-page">
      <nav className="navbar">
        <Link to="/" className="logo">Kiosk Self-Help</Link>
        <div className="nav-links">
          <Link to="/" className="nav-link">Home</Link>
        </div>
      </nav>

      <div className="client-content">
        <header className="client-header">
          <h1>Welcome to Your Resource Portal</h1>
          <p>Find the help and support you need</p>
        </header>

        <section className="search-section">
          <div className="search-box">
            <input 
              type="text" 
              placeholder="Search for services (e.g., housing, food, healthcare)..." 
              className="search-input"
            />
            <button className="search-button">Search</button>
          </div>
          <div className="quick-filters">
            <button className="filter-tag">🏠 Housing</button>
            <button className="filter-tag">🍽️ Food</button>
            <button className="filter-tag">🏥 Healthcare</button>
            <button className="filter-tag">💼 Jobs</button>
            <button className="filter-tag">📚 Education</button>
          </div>
        </section>

        <section className="resources-section">
          <h2>Available Resources</h2>
          <div className="resources-grid">
            <div className="resource-card">
              <div className="resource-header">
                <span className="resource-icon">🏠</span>
                <div>
                  <h3>Emergency Shelter Network</h3>
                  <span className="status available">Available Now</span>
                </div>
              </div>
              <p className="resource-description">
                Immediate emergency housing available 24/7. No appointment needed.
              </p>
              <div className="resource-details">
                <div className="detail-item">
                  <span className="detail-icon">📍</span>
                  <span>123 Main St, Downtown</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">📞</span>
                  <span>(555) 123-4567</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">🕐</span>
                  <span>Open 24/7</span>
                </div>
              </div>
              <button className="action-button">Get Directions</button>
            </div>

            <div className="resource-card">
              <div className="resource-header">
                <span className="resource-icon">🍽️</span>
                <div>
                  <h3>Community Food Bank</h3>
                  <span className="status available">Open Today</span>
                </div>
              </div>
              <p className="resource-description">
                Free meals and food assistance. Walk-ins welcome.
              </p>
              <div className="resource-details">
                <div className="detail-item">
                  <span className="detail-icon">📍</span>
                  <span>456 Oak Ave, Central</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">📞</span>
                  <span>(555) 234-5678</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">🕐</span>
                  <span>9 AM - 5 PM</span>
                </div>
              </div>
              <button className="action-button">Get Directions</button>
            </div>

            <div className="resource-card">
              <div className="resource-header">
                <span className="resource-icon">🏥</span>
                <div>
                  <h3>Mental Health Services</h3>
                  <span className="status limited">Limited Availability</span>
                </div>
              </div>
              <p className="resource-description">
                Free counseling and mental health support. Call for appointment.
              </p>
              <div className="resource-details">
                <div className="detail-item">
                  <span className="detail-icon">📍</span>
                  <span>789 Elm St, Westside</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">📞</span>
                  <span>(555) 345-6789</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">🕐</span>
                  <span>Mon-Fri 8 AM - 6 PM</span>
                </div>
              </div>
              <button className="action-button">Schedule Appointment</button>
            </div>

            <div className="resource-card">
              <div className="resource-header">
                <span className="resource-icon">💼</span>
                <div>
                  <h3>Job Training Center</h3>
                  <span className="status available">Enrolling Now</span>
                </div>
              </div>
              <p className="resource-description">
                Free job training, resume help, and interview preparation.
              </p>
              <div className="resource-details">
                <div className="detail-item">
                  <span className="detail-icon">📍</span>
                  <span>321 Career Blvd, East District</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">📞</span>
                  <span>(555) 456-7890</span>
                </div>
                <div className="detail-item">
                  <span className="detail-icon">🕐</span>
                  <span>Mon-Fri 9 AM - 4 PM</span>
                </div>
              </div>
              <button className="action-button">Learn More</button>
            </div>
          </div>
        </section>

        <section className="emergency-section">
          <div className="emergency-banner">
            <span className="emergency-icon">🚨</span>
            <div>
              <h3>Need Immediate Help?</h3>
              <p>
                Crisis Hotline: <strong>1-800-273-8255</strong> (24/7) | 
                Emergency: <strong>911</strong>
              </p>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
}

export default ClientPage;
