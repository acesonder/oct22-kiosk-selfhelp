import { Link } from 'react-router-dom';
import './ProviderPage.css';

function ProviderPage() {
  return (
    <div className="provider-page">
      <nav className="navbar">
        <Link to="/" className="logo">Kiosk Self-Help</Link>
        <div className="nav-links">
          <a href="#services" className="nav-link">Services</a>
          <a href="#analytics" className="nav-link">Analytics</a>
          <a href="#settings" className="nav-link">Settings</a>
          <Link to="/" className="nav-link">Logout</Link>
        </div>
      </nav>

      <div className="provider-content">
        <header className="provider-header">
          <div>
            <h1>Service Provider Dashboard</h1>
            <p>Manage your organization's services and availability</p>
          </div>
          <div className="organization-badge">
            <span className="org-icon">🏢</span>
            <div>
              <div className="org-name">Community Care Center</div>
              <div className="org-status">Verified Provider</div>
            </div>
          </div>
        </header>

        <div className="provider-stats">
          <div className="stat-card">
            <div className="stat-icon">👥</div>
            <div className="stat-info">
              <div className="stat-number">1,247</div>
              <div className="stat-label">People Served This Month</div>
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-icon">📊</div>
            <div className="stat-info">
              <div className="stat-number">87%</div>
              <div className="stat-label">Capacity Utilization</div>
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-icon">⭐</div>
            <div className="stat-info">
              <div className="stat-number">4.8</div>
              <div className="stat-label">Average Rating</div>
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-icon">🔄</div>
            <div className="stat-info">
              <div className="stat-number">34</div>
              <div className="stat-label">Pending Referrals</div>
            </div>
          </div>
        </div>

        <section className="services-section">
          <div className="section-header">
            <h2>Your Services</h2>
            <button className="primary-button">+ Add New Service</button>
          </div>

          <div className="services-grid">
            <div className="service-card">
              <div className="service-status-indicator active"></div>
              <div className="service-header">
                <span className="service-icon">🏠</span>
                <div className="service-toggle">
                  <label className="toggle-switch">
                    <input type="checkbox" checked readOnly />
                    <span className="toggle-slider"></span>
                  </label>
                </div>
              </div>
              <h3>Emergency Shelter</h3>
              <p className="service-description">24/7 emergency housing for individuals and families</p>
              <div className="service-info">
                <div className="info-item">
                  <span className="info-label">Capacity:</span>
                  <span className="info-value">45/50 beds</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Availability:</span>
                  <span className="info-value available">Available Now</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Requests Today:</span>
                  <span className="info-value">23</span>
                </div>
              </div>
              <button className="manage-button">Manage Service</button>
            </div>

            <div className="service-card">
              <div className="service-status-indicator active"></div>
              <div className="service-header">
                <span className="service-icon">🍽️</span>
                <div className="service-toggle">
                  <label className="toggle-switch">
                    <input type="checkbox" checked readOnly />
                    <span className="toggle-slider"></span>
                  </label>
                </div>
              </div>
              <h3>Meal Program</h3>
              <p className="service-description">Free meals three times daily, walk-ins welcome</p>
              <div className="service-info">
                <div className="info-item">
                  <span className="info-label">Capacity:</span>
                  <span className="info-value">150/200 daily</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Availability:</span>
                  <span className="info-value available">Open Today</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Requests Today:</span>
                  <span className="info-value">89</span>
                </div>
              </div>
              <button className="manage-button">Manage Service</button>
            </div>

            <div className="service-card">
              <div className="service-status-indicator limited"></div>
              <div className="service-header">
                <span className="service-icon">🏥</span>
                <div className="service-toggle">
                  <label className="toggle-switch">
                    <input type="checkbox" checked readOnly />
                    <span className="toggle-slider"></span>
                  </label>
                </div>
              </div>
              <h3>Counseling Services</h3>
              <p className="service-description">Individual and group therapy sessions</p>
              <div className="service-info">
                <div className="info-item">
                  <span className="info-label">Capacity:</span>
                  <span className="info-value">12/15 slots</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Availability:</span>
                  <span className="info-value limited">Limited</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Requests Today:</span>
                  <span className="info-value">8</span>
                </div>
              </div>
              <button className="manage-button">Manage Service</button>
            </div>

            <div className="service-card">
              <div className="service-status-indicator inactive"></div>
              <div className="service-header">
                <span className="service-icon">💼</span>
                <div className="service-toggle">
                  <label className="toggle-switch">
                    <input type="checkbox" readOnly />
                    <span className="toggle-slider"></span>
                  </label>
                </div>
              </div>
              <h3>Job Training</h3>
              <p className="service-description">Skills development and employment preparation</p>
              <div className="service-info">
                <div className="info-item">
                  <span className="info-label">Capacity:</span>
                  <span className="info-value">0/20 slots</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Availability:</span>
                  <span className="info-value unavailable">Currently Closed</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Requests Today:</span>
                  <span className="info-value">5</span>
                </div>
              </div>
              <button className="manage-button">Manage Service</button>
            </div>
          </div>
        </section>

        <section className="referrals-section">
          <h2>Recent Referrals</h2>
          <div className="referrals-list">
            <div className="referral-item">
              <div className="referral-info">
                <div className="referral-id">REF-2024-156</div>
                <div className="referral-service">Emergency Shelter</div>
                <div className="referral-time">2 hours ago</div>
              </div>
              <div className="referral-actions">
                <button className="accept-button">Accept</button>
                <button className="decline-button">Decline</button>
              </div>
            </div>

            <div className="referral-item">
              <div className="referral-info">
                <div className="referral-id">REF-2024-155</div>
                <div className="referral-service">Meal Program</div>
                <div className="referral-time">5 hours ago</div>
              </div>
              <div className="referral-actions">
                <button className="accept-button">Accept</button>
                <button className="decline-button">Decline</button>
              </div>
            </div>

            <div className="referral-item">
              <div className="referral-info">
                <div className="referral-id">REF-2024-154</div>
                <div className="referral-service">Counseling Services</div>
                <div className="referral-time">1 day ago</div>
              </div>
              <div className="referral-actions">
                <button className="accept-button">Accept</button>
                <button className="decline-button">Decline</button>
              </div>
            </div>
          </div>
        </section>

        <section className="analytics-preview">
          <h2>Analytics Overview</h2>
          <div className="chart-placeholder">
            <div className="chart-icon">📈</div>
            <p>Service utilization trends and impact metrics</p>
            <button className="view-analytics-button">View Full Analytics</button>
          </div>
        </section>
      </div>
    </div>
  );
}

export default ProviderPage;
