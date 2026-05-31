import numpy as np
import scipy.signal as signal
import matplotlib.pyplot as plt

# Contoh sinyal EEG dengan noise
fs = 256  # frekuensi sampling
t = np.linspace(0, 1, fs, endpoint=False)
eeg_signal = np.sin(2 * np.pi * 10 * t) + np.random.normal(0, 0.5, t.shape)  # sinyal EEG dengan noise

# Desain filter Butterworth
b, a = signal.butter(4, [1/(fs/2), 50/(fs/2)], btype='band')
filtered_signal = signal.filtfilt(b, a, eeg_signal)

# Visualisasi
plt.figure(figsize=(12, 6))
plt.subplot(2, 1, 1)
plt.title('Sinyal EEG dengan Noise')
plt.plot(t, eeg_signal)
plt.subplot(2, 1, 2)
plt.title('Sinyal EEG Setelah Filter')
plt.plot(t, filtered_signal)
plt.tight_layout()
plt.show()

# FFT untuk analisis frekuensi
frequencies = np.fft.fftfreq(len(filtered_signal), 1/fs)
fft_values = np.fft.fft(filtered_signal)

# Visualisasi spektrum
plt.figure(figsize=(8, 4))
plt.plot(frequencies[:len(frequencies)//2], np.abs(fft_values)[:len(fft_values)//2])
plt.title('Spektrum Frekuensi Sinyal EEG')
plt.xlabel('Frekuensi (Hz)')
plt.ylabel('Amplitudo')
plt.xlim(0, 50)  # Fokus pada frekuensi yang relevan
plt.show()