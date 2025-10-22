import { Link } from 'react-router-dom';
import './OutreachPage.css';

function OutreachPage() {
  return (
    <div className="outreach-page">
      <nav className="navbar">
        <Link to="/" className="logo">Kiosk Self-Help</Link>
        <div className="nav-links">
          <a href="#dashboard" className="nav-link">Dashboard</a>
          <a href="#clients" className="nav-link">Clients</a>
          <a href="#reports" className="nav-link">Reports</a>
          <Link to="/" className="nav-link">Logout</Link>
        </div>
      </nav>

      <div className="outreach-content">
        <header className="outreach-header">
          <h1>Outreach Staff Portal</h1>
          <p>Manage clients and coordinate services</p>
        </header>

        <div className="dashboard-stats">
          <div className="stat-card">
            <div className="stat-icon">👥</div>
            <div className="stat-info">
              <div className="stat-number">142</div>
              <div className="stat-label">Active Clients</div>
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-icon">📋</div>
            <div className="stat-info">
              <div className="stat-number">28</div>
              <div className="stat-label">Pending Follow-ups</div>
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-icon">✅</div>
            <div className="stat-info">
              <div className="stat-number">53</div>
              <div className="stat-label">Cases Resolved</div>
            </div>
          </div>
          <div className="stat-card">
            <div className="stat-icon">🏢</div>
            <div className="stat-info">
              <div className="stat-number">45</div>
              <div className="stat-label">Partner Organizations</div>
            </div>
          </div>
        </div>

        <section className="clients-section">
          <div className="section-header">
            <h2>Recent Client Interactions</h2>
            <button className="primary-button">+ Add New Client</button>
          </div>
          
          <div className="clients-table">
            <div className="table-header">
              <div className="table-cell">Client</div>
              <div className="table-cell">Status</div>
              <div className="table-cell">Services Needed</div>
              <div className="table-cell">Last Contact</div>
              <div className="table-cell">Actions</div>
            </div>
            
            <div className="table-row">
              <div className="table-cell">
                <div className="client-info">
                  <div className="client-avatar">JD</div>
                  <div>
                    <div className="client-name">John Doe</div>
                    <div className="client-id">ID: CL-2024-001</div>
                  </div>
                </div>
              </div>
              <div className="table-cell">
                <span className="status-badge active">Active</span>
              </div>
              <div className="table-cell">
                <div className="service-tags">
                  <span className="service-tag">Housing</span>
                  <span className="service-tag">Healthcare</span>
                </div>
              </div>
              <div className="table-cell">2 hours ago</div>
              <div className="table-cell">
                <button className="action-btn">View</button>
              </div>
            </div>

            <div className="table-row">
              <div className="table-cell">
                <div className="client-info">
                  <div className="client-avatar">MS</div>
                  <div>
                    <div className="client-name">Maria Santos</div>
                    <div className="client-id">ID: CL-2024-002</div>
                  </div>
                </div>
              </div>
              <div className="table-cell">
                <span className="status-badge followup">Follow-up Needed</span>
              </div>
              <div className="table-cell">
                <div className="service-tags">
                  <span className="service-tag">Food</span>
                  <span className="service-tag">Employment</span>
                </div>
              </div>
              <div className="table-cell">1 day ago</div>
              <div className="table-cell">
                <button className="action-btn">View</button>
              </div>
            </div>

            <div className="table-row">
              <div className="table-cell">
                <div className="client-info">
                  <div className="client-avatar">RJ</div>
                  <div>
                    <div className="client-name">Robert Johnson</div>
                    <div className="client-id">ID: CL-2024-003</div>
                  </div>
                </div>
              </div>
              <div className="table-cell">
                <span className="status-badge resolved">Resolved</span>
              </div>
              <div className="table-cell">
                <div className="service-tags">
                  <span className="service-tag">Mental Health</span>
                </div>
              </div>
              <div className="table-cell">3 days ago</div>
              <div className="table-cell">
                <button className="action-btn">View</button>
              </div>
            </div>
          </div>
        </section>

        <section className="tasks-section">
          <h2>Today's Tasks</h2>
          <div className="tasks-grid">
            <div className="task-card">
              <div className="task-header">
                <input type="checkbox" className="task-checkbox" />
                <div>
                  <h4>Follow up with Maria Santos</h4>
                  <p className="task-time">Due: 2:00 PM</p>
                </div>
              </div>
              <p className="task-description">Check on employment placement status</p>
            </div>

            <div className="task-card">
              <div className="task-header">
                <input type="checkbox" className="task-checkbox" />
                <div>
                  <h4>Complete case report for John Doe</h4>
                  <p className="task-time">Due: 4:00 PM</p>
                </div>
              </div>
              <p className="task-description">Monthly progress documentation required</p>
            </div>

            <div className="task-card">
              <div className="task-header">
                <input type="checkbox" className="task-checkbox" />
                <div>
                  <h4>Coordinate with housing provider</h4>
                  <p className="task-time">Due: 5:00 PM</p>
                </div>
              </div>
              <p className="task-description">Confirm placement availability for new client</p>
            </div>
          </div>
        </section>

        <section className="tools-section">
          <h2>Quick Tools</h2>
          <div className="tools-grid">
            <button className="tool-button">
              <span className="tool-icon">🔍</span>
              <span>Search Resources</span>
            </button>
            <button className="tool-button">
              <span className="tool-icon">📊</span>
              <span>Generate Report</span>
            </button>
            <button className="tool-button">
              <span className="tool-icon">📧</span>
              <span>Send Referral</span>
            </button>
            <button className="tool-button">
              <span className="tool-icon">📅</span>
              <span>Schedule Appointment</span>
            </button>
          </div>
        </section>
      </div>
    </div>
  );
}

export default OutreachPage;
