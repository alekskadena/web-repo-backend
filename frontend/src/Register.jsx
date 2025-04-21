import React, { useState } from 'react';

const RegisterForm = () => {
  const [formData, setFormData] = useState({
    fullname: '',
    email: '',
    username: '',
    password: '',
    confirm_password: '',
  });

  const [message, setMessage] = useState('');
  const [success, setSuccess] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch('http://localhost/web-repo-backend/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      });

      const result = await response.json();
      setMessage(result.message);
      setSuccess(result.success);

      if (result.success) {
        setFormData({
          fullname: '',
          email: '',
          username: '',
          password: '',
          confirm_password: '',
        });
      }
    } catch (error) {
      setMessage('Something went wrong. Please try again.');
      setSuccess(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-200">
      <div className="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 className="text-2xl font-bold text-center text-indigo-700 mb-6">
          Register to Apollo-Skies ✈️
        </h2>

        {message && (
          <div className={`mb-4 text-center text-sm ${success ? 'text-green-600' : 'text-red-600'}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          {['fullname', 'email', 'username', 'password', 'confirm_password'].map((field) => (
            <div key={field}>
              <label className="block mb-1 font-medium capitalize">
                {field.replace('_', ' ')}
              </label>
              <input
                type={field.includes('password') ? 'password' : field === 'email' ? 'email' : 'text'}
                name={field}
                value={formData[field]}
                onChange={handleChange}
                className="w-full border rounded-lg p-2"
                required
              />
            </div>
          ))}

          <button
            type="submit"
            className="w-full bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition"
          >
            Register
          </button>
        </form>
        <p className="mt-4 text-center text-sm">
          Already have an account?{' '}
          <a href="/login" className="text-indigo-600 hover:underline">
            Login here
          </a>
        </p>
      </div>
    </div>
  );
};

export default RegisterForm;
