import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Auth from './pages/Auth/Auth';
import Transactions from './pages/Transactions/Transactions';
import CreateTransaction from './pages/Transactions/CreateTransaction';

function App() {
  return (
    <Router>
      <Routes>
        {/*page par defaut*/}
        <Route path="/" element={<Auth />} />

        <Route path="/transactions" element={<Transactions />} />
        <Route path="/create_transaction" element={<CreateTransaction />} />

      </Routes>
    </Router>
  );
}

export default App;
