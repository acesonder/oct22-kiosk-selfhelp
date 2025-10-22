import { Routes, Route } from 'react-router-dom'
import './App.css'
import LandingPage from './pages/LandingPage'
import ClientPage from './pages/ClientPage'
import OutreachPage from './pages/OutreachPage'
import ProviderPage from './pages/ProviderPage'

function App() {
  return (
    <Routes>
      <Route path="/" element={<LandingPage />} />
      <Route path="/client" element={<ClientPage />} />
      <Route path="/outreach" element={<OutreachPage />} />
      <Route path="/provider" element={<ProviderPage />} />
    </Routes>
  )
}

export default App
