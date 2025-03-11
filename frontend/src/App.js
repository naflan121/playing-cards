import React, { useState } from 'react';
import './App.css';

// Get API URL from environment variable or use default
const API_URL =
  process.env.REACT_APP_API_URL ||
  "http://localhost/interview/playing-cards/backend";

function App() {
  const [numberOfPeople, setNumberOfPeople] = useState('');
  const [distribution, setDistribution] = useState([]);
  const [error, setError] = useState('');
  const [warning, setWarning] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setWarning('');
    setLoading(true);

    const num = parseInt(numberOfPeople);
    if (num > 52) {
      setWarning('Note: Only the first 52 people will receive cards..');
    }

    try {
      const response = await fetch(`${API_URL}/`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ numberOfPeople: num }),
      });

      const result = await response.json();
      
      if (!result.success) {
        throw new Error(result.error);
      }

      setDistribution(result.data);
    } catch (err) {
      setError(err.message || 'Input value does not exist or value is invalid');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="App">
      <header className="App-header">
        <h1>Playing Cards Distribution</h1>
      </header>
      <main>
        <form onSubmit={handleSubmit}>
          <div className="input-group">
            <label htmlFor="numberOfPeople">Number of People:</label>
            <input
              type="number"
              id="numberOfPeople"
              value={numberOfPeople}
              onChange={(e) => setNumberOfPeople(e.target.value)}
              min="1"
              required
            />
          </div>
          <button type="submit" disabled={loading}>
            {loading ? 'Distributing...' : 'Distribute Cards'}
          </button>
        </form>

        {error && <div className="error">{error}</div>}
        {warning && <div className="warning">{warning}</div>}

        {distribution.length > 0 && (
          <div className="results">
            <h2>Card Distribution</h2>
            <div className="distribution-list">
              {distribution.map((cards, index) => (
                <div key={index} className="person-cards">
                  <strong>Person {index + 1}:</strong>
                  <span>{cards || '(No cards)'}</span>
                </div>
              ))}
            </div>
          </div>
        )}
      </main>
    </div>
  );
}

export default App;
