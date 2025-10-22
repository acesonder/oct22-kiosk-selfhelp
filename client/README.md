# Kiosk Self-Help Platform - Client Application

A comprehensive web-based application designed to connect individuals in need with resources for homeless services, addiction recovery, mental health support, and other essential services. The platform serves three distinct user groups: clients seeking help, outreach staff managing cases, and service providers offering resources.

## Features

### 🤝 Client Portal
- **Resource Discovery**: Search and browse available services by category (housing, food, healthcare, employment, education)
- **Quick Filters**: Instantly filter resources by service type
- **Detailed Information**: View service details including location, contact information, and availability
- **Emergency Access**: Quick access to crisis hotlines and emergency services

### 👨‍💼 Outreach Staff Portal
- **Client Management**: Track and manage active clients and their service needs
- **Case Tracking**: Monitor client progress and follow-up requirements
- **Task Management**: Organize daily tasks and appointments
- **Quick Tools**: Access resources, generate reports, send referrals, and schedule appointments
- **Dashboard Analytics**: View key metrics including active clients, pending follow-ups, and resolved cases

### 🏢 Service Provider Portal
- **Service Management**: Manage organization services, capacity, and availability
- **Real-time Status**: Toggle service availability and update capacity in real-time
- **Referral Management**: Accept or decline incoming client referrals
- **Analytics Dashboard**: View utilization metrics, ratings, and impact statistics
- **Organization Profile**: Display verified provider status and organization details

## Technology Stack

- **Frontend Framework**: React 19.1.1
- **Build Tool**: Vite 7.1.7
- **Routing**: React Router DOM 7.9.4
- **Styling**: CSS3 with custom responsive design
- **Development**: Hot Module Replacement (HMR) for rapid development

## Getting Started

### Prerequisites

- Node.js (v16 or higher)
- npm (v7 or higher)

### Installation

Install dependencies:
```bash
npm install
```

### Development

Start the development server:
```bash
npm run dev
```

The application will be available at `http://localhost:5173`

### Build

Create a production build:
```bash
npm run build
```

The built files will be in the `dist` directory.

### Preview Production Build

Preview the production build locally:
```bash
npm run preview
```

### Linting

Run ESLint to check code quality:
```bash
npm run lint
```

## Project Structure

```
src/
├── pages/
│   ├── LandingPage.jsx      # Main landing page
│   ├── LandingPage.css
│   ├── ClientPage.jsx       # Client-facing interface
│   ├── ClientPage.css
│   ├── OutreachPage.jsx     # Outreach staff interface
│   ├── OutreachPage.css
│   ├── ProviderPage.jsx     # Service provider interface
│   └── ProviderPage.css
├── App.jsx                   # Main app component with routing
├── App.css
├── main.jsx                  # Application entry point
└── index.css                 # Global styles
```

## Routes

- `/` - Landing page with feature highlights
- `/client` - Client resource portal
- `/outreach` - Outreach staff management dashboard
- `/provider` - Service provider dashboard

## Features Highlights

### Platform Features
- 🏠 **Housing Support**: Emergency shelters, transitional housing, and permanent housing solutions
- 🏥 **Health Services**: Mental health support, addiction recovery programs, and medical care
- 🍽️ **Food & Basic Needs**: Food banks, meal programs, and essential item assistance
- 💼 **Employment Services**: Job training, placement assistance, and career counseling
- 📚 **Education & Training**: Educational programs, skill development, and certifications
- 👥 **Community Support**: Support groups, counseling, and peer assistance networks

### Tools & Services
- 🔍 **Resource Finder**: Search and filter services by location, type, and availability
- 📱 **Mobile Access**: Responsive design for any device
- 🗂️ **Case Management**: Track progress, appointments, and service utilization
- 📊 **Analytics & Reporting**: Data-driven insights for service delivery
- 🔒 **Secure & Private**: HIPAA-compliant platform protecting client confidentiality
- 🌐 **Multi-Language Support**: Services available in multiple languages

## Screenshots

### Landing Page
![Landing Page](https://github.com/user-attachments/assets/6c02cf96-be55-494b-a43f-fa928c9fb5d5)

### Client Portal
![Client Portal](https://github.com/user-attachments/assets/03e272f4-bb2d-49a6-9fcf-08b7bc09fe0a)

### Outreach Staff Portal
![Outreach Staff Portal](https://github.com/user-attachments/assets/a96103ac-318c-4699-9c42-0fb0d87a025a)

### Service Provider Dashboard
![Service Provider Dashboard](https://github.com/user-attachments/assets/73f01d62-a3c7-4b1d-a318-420ba71a09d7)
