<style>
.register-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

form {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: bold;
}

input[type="text"],
input[type="password"],
input[type="email"],
input[type="date"],
select {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

select {
    background-color: white;
}

button[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="submit"]:hover {
    background-color: #45a049;
}
</style>

<div class="register-container">
    <h2>Registrasi Pengguna</h2>
    <form action="simpan_user.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" required>

        <label>Email:</label>
        <input type="email" name="email">

        <label>No. HP:</label>
        <input type="text" name="no_hp">

        <label>Agama:</label>
        <input type="text" name="agama" required>

        <label>Kebangsaan:</label>
        <input type="text" name="kebangsaan" required>

        <label>No. KTP:</label>
        <input type="text" name="noktp" required>

        <label>Tempat Tanggal Lahir:</label>
        <input type="text" name="tempat_lahir" required>
        <input type="date" name="tanggal_lahir" required>

        <label>Jenis Kelamin:</label>
        <select name="jenis_kelamin" required>
            <option value="">Pilih Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>

        <button type="submit">Daftar</button>
    </form>
</div>
