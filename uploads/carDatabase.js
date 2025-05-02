import React from 'react';
import './CarDatabase.css';

const Header = () => (
  <header>
    <h1 className="header-text">cardatabase</h1>
  </header>
);

const Navigation = () => (
  <nav>
    <a href="#" className="active">Home</a>
    <a href="#">Cars</a>
    <a href="#">Upload</a>
  </nav>
);

const CarTable = () => (
  <div className="table-container">
    <table>
      <thead>
        <tr>
          <th>Car</th>
          <th>Model</th>
          <th>Image</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Audi</td>
          <td>A4</td>
          <td><img src="uploads/sample.jpg" alt="Car" /></td>
        </tr>
      </tbody>
    </table>
  </div>
);

const Popup = () => (
  <>
    <div className="overlay" id="overlay"></div>
    <div className="popup" id="popup">
      <div className="popup-image-wrapper">
        <button
          onClick={() => window.print()}
          title="Print"
          style={{
            position: 'absolute',
            top: 0,
            right: 5,
            background: 'none',
            border: 'none',
            cursor: 'pointer'
          }}
        >
          <img
            src="uploads/printicon.png"
            alt="Print"
            style={{ width: '40px', height: '40px' }}
          />
        </button>
        <button className="arrow left-arrow">⬅️</button>
        <img id="popup-img" src="uploads/sample.jpg" alt="Popup" />
        <button className="arrow right-arrow">➡️</button>
      </div>
      <p>Optional details or checkbox here</p>
      <input type="checkbox" /> Confirm
    </div>
  </>
);

const Footer = () => (
  <footer>
    &copy; {new Date().getFullYear()} Car Database. All rights reserved.
  </footer>
);

const CarDatabase = () => {
  return (
    <div className="car-database">
      <Header />
      <Navigation />
      <CarTable />
      <Popup />
      <Footer />
    </div>
  );
};

export default CarDatabase;
