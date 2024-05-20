<?

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UploadedFile;
use App\Services\PdfService;

class FileUploadController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function uploadPdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf'
        ]);

        $file = $request->file('file');

        // Pseudo code to check if the file contains the word "Proposal"
        if (!$this->pdfService->searchFor($file, 'Proposal')) {
            return response()->json(['error' => 'The PDF does not contain the word "Proposal".'], 422);
        }

        $filename = $file->getClientOriginalName();
        $filesize = $file->getSize();

        // Check if a file with the same name and size already exists
        $existingFile = UploadedFile::where('name', $filename)
                                    ->where('size', $filesize)
                                    ->first();

        if ($existingFile) {
            // Update existing file
            Storage::delete($existingFile->path);
            $path = $file->store('uploads');
            $existingFile->update(['path' => $path]);
        } else {
            // Create a new file record
            $path = $file->store('uploads');
            UploadedFile::create([
                'name' => $filename,
                'size' => $filesize,
                'path' => $path
            ]);
        }

        return response()->json(['success' => 'File uploaded successfully.']);
    }
}