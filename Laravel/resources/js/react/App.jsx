import React from 'react';
import { createRoot } from 'react-dom/client';
import Login from './pages/login';
import '../../css/app.css';

const container = document.getElementById('root');
const root = createRoot(container);
root.render(<Login />);
